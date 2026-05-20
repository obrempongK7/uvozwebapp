<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class AdminCampaignController extends Controller {
    public function index(Request $request) { $campaigns = Campaign::with('advertiser')->when($request->status,fn($q)=>$q->where('status',$request->status))->orderByDesc('created_at')->paginate(25); return view('admin.campaigns', compact('campaigns')); }
    public function approve(Campaign $campaign) { $campaign->update(['status'=>'active','reviewed_by'=>\Auth::id()]); return back()->with('success','Campaign approved.'); }
    public function reject(Request $request, Campaign $campaign) { $request->validate(['reason'=>['required']]); $campaign->update(['status'=>'rejected','rejection_reason'=>$request->reason,'reviewed_by'=>\Auth::id()]); return back()->with('success','Campaign rejected.'); }
    public function analytics(Campaign $campaign) { return view('admin.campaign-analytics', compact('campaign')); }
}
