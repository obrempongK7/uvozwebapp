<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\VoicePost;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VoiceController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function index()
    {
        $posts = VoicePost::with('user')
            ->where('status', 'published')->where('visibility', 'public')
            ->orderByDesc('published_at')->paginate(12);
        return view('voice.index', compact('posts'));
    }

    public function create()
    {
        return view('voice.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required','string','max:200'],
            'description' => ['nullable','string','max:2000'],
            'audio'       => ['required','file','mimes:mp3,m4a,aac,wav','max:51200'],
            'cover'       => ['nullable','image','max:5120'],
            'visibility'  => ['required','in:public,followers,private'],
            'hashtags'    => ['nullable','string'],
        ]);

        $audioPath = $request->file('audio')->store('voice-posts', 'public');
        $coverPath = $request->hasFile('cover')
            ? $request->file('cover')->store('voice-covers', 'public') : null;

        $hashtags = [];
        if ($request->hashtags) {
            preg_match_all('/#(\w+)/', $request->hashtags, $matches);
            $hashtags = $matches[1] ?? [];
        }

        // Get audio duration (simplified - in production use FFMpeg)
        $duration = 0;
        $fileSize = $request->file('audio')->getSize();

        $post = VoicePost::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'audio_file'  => $audioPath,
            'audio_format'=> $request->file('audio')->getClientOriginalExtension(),
            'duration'    => $duration,
            'file_size'   => $fileSize,
            'cover_image' => $coverPath,
            'hashtags'    => $hashtags,
            'visibility'  => $request->visibility,
            'status'      => 'published',
            'country'     => Auth::user()->country,
            'published_at'=> now(),
        ]);

        // Update hashtag counts
        foreach ($hashtags as $tag) {
            \App\Models\Hashtag::firstOrCreate(['name' => strtolower($tag)])
                ->increment('posts_count');
        }

        // Award points for publishing
        $points = $this->walletService->getRewardRate('voice_post_published', Auth::user()->country);
        if ($points > 0) {
            $this->walletService->awardPoints(Auth::user(), $points, 'points_earned', 'Published a voice post', $post);
        }

        Auth::user()->profile?->increment('voice_posts_count');

        return redirect()->route('voice.show', $post)->with('success', 'Voice post published!');
    }

    public function show(VoicePost $voicePost)
    {
        abort_if($voicePost->visibility === 'private' && $voicePost->user_id !== Auth::id(), 404);

        $voicePost->load(['user', 'comments.user']);
        $comments = $voicePost->comments()->whereNull('parent_id')->with(['user','replies.user'])
            ->orderByDesc('created_at')->paginate(10);

        return view('voice.show', compact('voicePost', 'comments'));
    }

    public function edit(VoicePost $voicePost)
    {
        abort_if($voicePost->user_id !== Auth::id(), 403);
        return view('voice.edit', compact('voicePost'));
    }

    public function update(Request $request, VoicePost $voicePost)
    {
        abort_if($voicePost->user_id !== Auth::id(), 403);
        $request->validate([
            'title'      => ['required','string','max:200'],
            'description'=> ['nullable','string','max:2000'],
            'visibility' => ['required','in:public,followers,private'],
        ]);

        $voicePost->update($request->only('title','description','visibility'));
        return redirect()->route('voice.show', $voicePost)->with('success', 'Post updated.');
    }

    public function destroy(VoicePost $voicePost)
    {
        abort_if($voicePost->user_id !== Auth::id() && !Auth::user()->isAdmin(), 403);
        $voicePost->delete();
        return redirect()->route('voice.index')->with('success', 'Post deleted.');
    }

    public function recordPlay(Request $request, VoicePost $voicePost)
    {
        $user = Auth::user();
        // Prevent self-play rewards
        if ($voicePost->user_id === $user->id) {
            $voicePost->increment('plays_count');
            return response()->json(['success' => true]);
        }

        // Check if already played in last 24h
        $alreadyPlayed = \App\Models\PostView::where('user_id', $user->id)
            ->where('viewable_id', $voicePost->id)
            ->where('viewable_type', VoicePost::class)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if (!$alreadyPlayed) {
            \App\Models\PostView::create([
                'user_id'       => $user->id,
                'viewable_id'   => $voicePost->id,
                'viewable_type' => VoicePost::class,
                'ip_address'    => $request->ip(),
            ]);

            $voicePost->increment('plays_count');

            // Award post owner
            $ownerPoints = $this->walletService->getRewardRate('voice_play_received', $voicePost->user->country);
            if ($ownerPoints > 0) {
                $this->walletService->awardPoints($voicePost->user, $ownerPoints, 'points_earned', 'Voice post played', $voicePost);
            }

            // Award listener
            $listenerPoints = $this->walletService->getRewardRate('voice_listen', $user->country);
            if ($listenerPoints > 0) {
                $this->walletService->awardPoints($user, $listenerPoints, 'points_earned', 'Listened to a voice post', $voicePost);
            }

            // Update engagement score
            $voicePost->increment('engagement_score', 1);
        }

        return response()->json(['success' => true, 'plays' => $voicePost->fresh()->plays_count]);
    }

    public function like(VoicePost $voicePost)
    {
        $user = Auth::user();
        $existing = \App\Models\Reaction::where('user_id', $user->id)
            ->where('reactable_id', $voicePost->id)
            ->where('reactable_type', VoicePost::class)->first();

        if ($existing) {
            $existing->delete();
            $voicePost->decrement('likes_count');
            return response()->json(['liked' => false, 'count' => $voicePost->fresh()->likes_count]);
        }

        \App\Models\Reaction::create([
            'user_id'       => $user->id,
            'reactable_id'  => $voicePost->id,
            'reactable_type'=> VoicePost::class,
            'type'          => 'like',
        ]);
        $voicePost->increment('likes_count');
        $voicePost->increment('engagement_score', 2);

        return response()->json(['liked' => true, 'count' => $voicePost->fresh()->likes_count]);
    }

    public function repost(VoicePost $voicePost)
    {
        $user = Auth::user();
        $existing = \App\Models\Repost::where('user_id', $user->id)
            ->where('repostable_id', $voicePost->id)
            ->where('repostable_type', VoicePost::class)->first();

        if ($existing) {
            $existing->delete();
            $voicePost->decrement('reposts_count');
        } else {
            \App\Models\Repost::create([
                'user_id'        => $user->id,
                'repostable_id'  => $voicePost->id,
                'repostable_type'=> VoicePost::class,
            ]);
            $voicePost->increment('reposts_count');
        }

        return response()->json(['count' => $voicePost->fresh()->reposts_count]);
    }

    public function comment(Request $request, VoicePost $voicePost)
    {
        $request->validate([
            'content'    => ['required','string','max:1000'],
            'parent_id'  => ['nullable','exists:comments,id'],
        ]);

        $comment = \App\Models\Comment::create([
            'user_id'        => Auth::id(),
            'commentable_id' => $voicePost->id,
            'commentable_type'=> VoicePost::class,
            'content'        => $request->content,
            'parent_id'      => $request->parent_id,
        ]);

        $voicePost->increment('comments_count');

        // Award commenter
        $points = $this->walletService->getRewardRate('comment_posted', Auth::user()->country);
        if ($points > 0) {
            $this->walletService->awardPoints(Auth::user(), $points, 'points_earned', 'Posted a comment', $comment);
        }

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user'),
        ]);
    }

    public function report(Request $request, VoicePost $voicePost)
    {
        $request->validate(['reason' => ['required','string'], 'details' => ['nullable','string']]);
        \App\Models\Report::create([
            'reporter_id'   => Auth::id(),
            'reportable_id' => $voicePost->id,
            'reportable_type'=> VoicePost::class,
            'reason'        => $request->reason,
            'details'       => $request->details,
        ]);
        return response()->json(['success' => true]);
    }
}
