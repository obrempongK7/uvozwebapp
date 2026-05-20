<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\KycVerification;
use Illuminate\Http\Request;

class AdminKycController extends Controller {
    public function index(Request $request) { $kycs = KycVerification::with('user')->when($request->status,fn($q)=>$q->where('status',$request->status))->orderByDesc('created_at')->paginate(25); return view('admin.kyc', compact('kycs')); }
    public function show(KycVerification $kyc) { $kyc->load('user'); return view('admin.kyc-show', compact('kyc')); }
    public function approve(KycVerification $kyc) { $kyc->update(['status'=>'approved','reviewed_by'=>\Auth::id(),'reviewed_at'=>now()]); $kyc->user->update(['is_verified'=>true]); return back()->with('success','KYC approved. User verified.'); }
    public function reject(Request $request, KycVerification $kyc) { $request->validate(['reason'=>['required']]); $kyc->update(['status'=>'rejected','reviewed_by'=>\Auth::id(),'reviewed_at'=>now(),'rejection_reason'=>$request->reason]); return back()->with('success','KYC rejected.'); }
}
