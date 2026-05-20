<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\VoicePost;
use App\Models\StatusPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $type  = request('type', 'for-you');
        $posts = $this->getFeedPosts($user, $type);

        // Campaigns for the feed
        $campaigns = \App\Models\Campaign::where('status','active')
            ->where(function($q) use ($user) {
                $q->whereNull('target_countries')
                  ->orWhereJsonContains('target_countries', $user->country);
            })
            ->whereNotExists(function($q) use ($user) {
                $q->from('campaign_interactions')
                  ->whereColumn('campaign_id','campaigns.id')
                  ->where('user_id', $user->id);
            })
            ->inRandomOrder()->limit(3)->get();

        return view('user.feed', compact('posts', 'type', 'campaigns'));
    }

    public function following()
    {
        $user  = Auth::user();
        $followingIds = $user->following()->pluck('id');

        $posts = VoicePost::with('user')
            ->whereIn('user_id', $followingIds)
            ->where('status','published')
            ->whereIn('visibility',['public','followers'])
            ->orderByDesc('published_at')
            ->paginate(20);

        return view('user.feed', ['posts' => $posts, 'type' => 'following', 'campaigns' => collect()]);
    }

    public function trending()
    {
        $posts = VoicePost::with('user')
            ->where('status','published')->where('visibility','public')
            ->where('published_at','>=', now()->subDays(7))
            ->orderByDesc('engagement_score')
            ->paginate(20);

        return view('user.feed', ['posts' => $posts, 'type' => 'trending', 'campaigns' => collect()]);
    }

    public function explore()
    {
        $voicePosts = VoicePost::with('user')
            ->where('status','published')->where('visibility','public')
            ->orderByDesc('engagement_score')->limit(12)->get();

        $podcasts = \App\Models\Podcast::with('user')
            ->where('status','active')
            ->orderByDesc('subscribers_count')->limit(8)->get();

        $topUsers = \App\Models\User::with('profile')
            ->where('is_active',true)->where('is_banned',false)
            ->whereHas('profile', fn($q) => $q->orderByDesc('followers_count'))
            ->limit(10)->get();

        $liveRooms = \App\Models\LiveRoom::with('host')
            ->where('status','live')->limit(6)->get();

        $trendingHashtags = \App\Models\Hashtag::where('is_trending',true)
            ->orderByDesc('posts_count')->limit(20)->get();

        return view('user.explore', compact('voicePosts','podcasts','topUsers','liveRooms','trendingHashtags'));
    }

    private function getFeedPosts($user, string $type)
    {
        $query = VoicePost::with('user')->where('status','published')->where('visibility','public');

        if ($type === 'nearby' && $user->country) {
            $query->where('country', $user->country);
        } elseif ($type === 'for-you') {
            $query->orderByDesc('engagement_score');
        } else {
            $query->orderByDesc('published_at');
        }

        return $query->paginate(15);
    }
}
