<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\StatusPost;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller {
    public function __construct(private WalletService $ws) {}
    public function index() {
        $posts = StatusPost::with('user')->where('status','published')->where('visibility','public')
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at','>',now()))
            ->orderByDesc('created_at')->paginate(20);
        return view('status.index', compact('posts'));
    }
    public function store(Request $request) {
        $request->validate(['type'=>['required','in:text,image,video,link'],'content'=>['nullable','string','max:2000']]);
        $media = null;
        if ($request->hasFile('media')) $media = $request->file('media')->store('status-media','public');
        $post = StatusPost::create([
            'user_id'=>Auth::id(),'type'=>$request->type,'content'=>$request->content,
            'media_file'=>$media,'link_url'=>$request->link_url,'contact_buttons'=>$request->contact_buttons,
            'is_monetized'=>$request->boolean('is_monetized'),'view_price'=>$request->view_price??0,
            'expires_at'=>$request->expires_at,'visibility'=>$request->visibility??'public','status'=>'published',
            'country'=>Auth::user()->country,
        ]);
        return response()->json(['success'=>true,'post'=>$post]);
    }
    public function show(StatusPost $statusPost) {
        abort_if($statusPost->visibility==='private'&&$statusPost->user_id!==Auth::id(),404);
        $statusPost->load('user');
        return view('status.show', compact('statusPost'));
    }
    public function destroy(StatusPost $statusPost) {
        abort_if($statusPost->user_id!==Auth::id()&&!Auth::user()->isAdmin(),403);
        $statusPost->delete();
        return response()->json(['success'=>true]);
    }
    public function recordView(Request $request, StatusPost $statusPost) {
        $user = Auth::user();
        if ($statusPost->user_id===$user->id) { $statusPost->increment('views_count'); return response()->json(['success'=>true]); }
        $seen = \App\Models\PostView::where('user_id',$user->id)->where('viewable_id',$statusPost->id)->where('viewable_type',StatusPost::class)->exists();
        if (!$seen) {
            \App\Models\PostView::create(['user_id'=>$user->id,'viewable_id'=>$statusPost->id,'viewable_type'=>StatusPost::class,'ip_address'=>$request->ip()]);
            $statusPost->increment('views_count');
            if ($statusPost->is_monetized && $statusPost->view_price > 0) {
                $this->ws->deductPoints($user,$statusPost->view_price,'Viewed monetized status');
                $this->ws->awardPoints($statusPost->user,$statusPost->view_price,'points_earned','Status view earnings',$statusPost);
            }
        }
        return response()->json(['success'=>true]);
    }
    public function like(StatusPost $statusPost) {
        $r = \App\Models\Reaction::where('user_id',Auth::id())->where('reactable_id',$statusPost->id)->where('reactable_type',StatusPost::class)->first();
        if ($r) { $r->delete(); $statusPost->decrement('likes_count'); return response()->json(['liked'=>false]); }
        \App\Models\Reaction::create(['user_id'=>Auth::id(),'reactable_id'=>$statusPost->id,'reactable_type'=>StatusPost::class,'type'=>'like']);
        $statusPost->increment('likes_count');
        return response()->json(['liked'=>true]);
    }
    public function contactClick(StatusPost $statusPost) {
        $statusPost->increment('contact_clicks');
        $points = $this->ws->getRewardRate('contact_click',$statusPost->user->country);
        if ($points>0) $this->ws->awardPoints($statusPost->user,$points,'points_earned','Contact button clicked',$statusPost);
        return response()->json(['success'=>true]);
    }
    public function report(Request $request, StatusPost $statusPost) {
        \App\Models\Report::create(['reporter_id'=>Auth::id(),'reportable_id'=>$statusPost->id,'reportable_type'=>StatusPost::class,'reason'=>$request->reason,'details'=>$request->details]);
        return response()->json(['success'=>true]);
    }
}
