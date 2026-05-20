<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignInteraction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller {
    public function __construct(private WalletService $ws) {}
    public function index() {
        $user = Auth::user();
        $campaigns = Campaign::where('status','active')
            ->where(fn($q)=>$q->whereNull('target_countries')->orWhereJsonContains('target_countries',$user->country))
            ->whereNotExists(fn($q)=>$q->from('campaign_interactions')->whereColumn('campaign_id','campaigns.id')->where('user_id',$user->id))
            ->orderByDesc('points_per_action')->paginate(20);
        return view('campaigns.index', compact('campaigns'));
    }
    public function create() { return view('campaigns.create'); }
    public function store(Request $request) {
        $request->validate(['title'=>['required','string'],'type'=>['required','in:voice_listen,status_view,link_click,app_install,survey'],'budget'=>['required','numeric','min:10'],'points_per_action'=>['required','integer','min:1']]);
        $cover = $request->hasFile('cover_image') ? $request->file('cover_image')->store('campaigns','public') : null;
        Campaign::create(['advertiser_id'=>Auth::id(),'title'=>$request->title,'description'=>$request->description,'cover_image'=>$cover,'type'=>$request->type,'target_url'=>$request->target_url,'target_countries'=>$request->target_countries?explode(',',$request->target_countries):null,'budget'=>$request->budget,'points_per_action'=>$request->points_per_action,'max_actions'=>$request->max_actions,'status'=>'pending','starts_at'=>$request->starts_at,'ends_at'=>$request->ends_at]);
        return redirect()->route('campaign.mine')->with('success','Campaign submitted for review.');
    }
    public function show(Campaign $campaign) { return view('campaigns.show', compact('campaign')); }
    public function interact(Request $request, Campaign $campaign) {
        $user = Auth::user();
        if ($campaign->status!=='active') return response()->json(['error'=>'Campaign not active'],422);
        $exists = CampaignInteraction::where('campaign_id',$campaign->id)->where('user_id',$user->id)->exists();
        if ($exists) return response()->json(['error'=>'Already completed'],422);
        CampaignInteraction::create(['campaign_id'=>$campaign->id,'user_id'=>$user->id,'status'=>'completed','points_earned'=>$campaign->points_per_action,'ip_address'=>$request->ip()]);
        $campaign->increment('completed_actions');
        $campaign->increment('spent',$campaign->points_per_action / 1000);
        $this->ws->awardPoints($user,$campaign->points_per_action,'campaign_reward',"Completed campaign: {$campaign->title}",$campaign);
        return response()->json(['success'=>true,'points'=>$campaign->points_per_action]);
    }
    public function myCampaigns() { $campaigns = Campaign::where('advertiser_id',Auth::id())->orderByDesc('created_at')->paginate(20); return view('campaigns.mine', compact('campaigns')); }
    public function analytics(Campaign $campaign) { abort_if($campaign->advertiser_id!==Auth::id()&&!Auth::user()->isAdmin(),403); $stats = ['total'=>$campaign->completed_actions,'budget_used'=>$campaign->spent,'remaining'=>$campaign->remaining_budget]; return view('campaigns.analytics', compact('campaign','stats')); }
}
