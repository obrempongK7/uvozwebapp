<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PodcastController extends Controller {
    public function __construct(private WalletService $ws) {}
    public function index() { $podcasts = Podcast::with('user')->where('status','active')->orderByDesc('subscribers_count')->paginate(20); return view('podcast.index', compact('podcasts')); }
    public function create() { return view('podcast.create'); }
    public function store(Request $request) {
        $request->validate(['title'=>['required','string','max:200'],'description'=>['nullable','string'],'type'=>['required','in:free,premium,paid']]);
        $cover = $request->hasFile('cover_artwork') ? $request->file('cover_artwork')->store('podcast-covers','public') : null;
        $podcast = Podcast::create(['user_id'=>Auth::id(),'title'=>$request->title,'description'=>$request->description,'cover_artwork'=>$cover,'category'=>$request->category,'tags'=>$request->tags?explode(',',$request->tags):null,'type'=>$request->type,'price'=>$request->price??0,'status'=>'active','language'=>$request->language??'en','country'=>Auth::user()->country]);
        return redirect()->route('podcast.show',$podcast)->with('success','Podcast created!');
    }
    public function show(Podcast $podcast) { $podcast->load('user'); $episodes = $podcast->episodes()->where('status','published')->orderByDesc('published_at')->paginate(20); $isSubscribed = Auth::check()&&\App\Models\PodcastSubscriber::where('podcast_id',$podcast->id)->where('user_id',Auth::id())->exists(); return view('podcast.show', compact('podcast','episodes','isSubscribed')); }
    public function edit(Podcast $podcast) { abort_if($podcast->user_id!==Auth::id(),403); return view('podcast.edit', compact('podcast')); }
    public function update(Request $request, Podcast $podcast) { abort_if($podcast->user_id!==Auth::id(),403); $podcast->update($request->except('_token','_method','cover_artwork')); return back()->with('success','Podcast updated.'); }
    public function destroy(Podcast $podcast) { abort_if($podcast->user_id!==Auth::id(),403); $podcast->delete(); return redirect()->route('podcast.index')->with('success','Podcast deleted.'); }
    public function subscribe(Podcast $podcast) { \App\Models\PodcastSubscriber::firstOrCreate(['podcast_id'=>$podcast->id,'user_id'=>Auth::id()]); $podcast->increment('subscribers_count'); return response()->json(['subscribed'=>true]); }
    public function unsubscribe(Podcast $podcast) { \App\Models\PodcastSubscriber::where('podcast_id',$podcast->id)->where('user_id',Auth::id())->delete(); $podcast->decrement('subscribers_count'); return response()->json(['subscribed'=>false]); }
    public function storeEpisode(Request $request, Podcast $podcast) {
        abort_if($podcast->user_id!==Auth::id(),403);
        $request->validate(['title'=>['required','string','max:200'],'audio'=>['required','file','mimes:mp3,m4a,aac,wav','max:102400']]);
        $audio = $request->file('audio')->store('podcast-episodes','public');
        $ep = PodcastEpisode::create(['podcast_id'=>$podcast->id,'user_id'=>Auth::id(),'title'=>$request->title,'show_notes'=>$request->show_notes,'audio_file'=>$audio,'duration'=>0,'file_size'=>$request->file('audio')->getSize(),'episode_number'=>$request->episode_number,'type'=>$request->type??'full','access'=>$request->access??'free','status'=>'published','published_at'=>now()]);
        $podcast->increment('episodes_count');
        return redirect()->route('podcast.episodes.show',[$podcast,$ep])->with('success','Episode published!');
    }
    public function showEpisode(Podcast $podcast, PodcastEpisode $episode) { return view('podcast.episode', compact('podcast','episode')); }
    public function recordPlay(Request $request, Podcast $podcast, PodcastEpisode $episode) {
        $episode->increment('plays_count');
        $pts = $this->ws->getRewardRate('podcast_listen',Auth::user()->country);
        if ($pts>0) $this->ws->awardPoints(Auth::user(),$pts,'points_earned','Listened to podcast',$episode);
        return response()->json(['success'=>true]);
    }
    public function destroyEpisode(Podcast $podcast, PodcastEpisode $episode) { abort_if($podcast->user_id!==Auth::id(),403); $episode->delete(); $podcast->decrement('episodes_count'); return back()->with('success','Episode deleted.'); }
}
