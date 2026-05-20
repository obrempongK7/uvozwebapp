<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SubscriptionPlan;
use App\Models\FraudFlag;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller {
    public function index() { return view('admin.settings.index'); }
    public function updateGeneral(Request $request) {
        foreach ($request->except('_token') as $k=>$v) SiteSetting::set($k,$v,'general');
        return back()->with('success','Settings saved.');
    }
    public function updatePayment(Request $request) { foreach ($request->except('_token') as $k=>$v) SiteSetting::set($k,$v,'payment'); return back()->with('success','Payment settings saved.'); }
    public function updateRewards(Request $request) { foreach ($request->except('_token') as $k=>$v) SiteSetting::set($k,$v,'rewards'); return back()->with('success','Reward settings saved.'); }
    public function updateSeo(Request $request) { foreach ($request->except('_token') as $k=>$v) SiteSetting::set($k,$v,'seo'); return back()->with('success','SEO settings saved.'); }
    public function fraud() { $flags = FraudFlag::with('user')->where('status','open')->orderByDesc('created_at')->paginate(25); return view('admin.settings.fraud', compact('flags')); }
    public function fraudAction(Request $request, FraudFlag $flag) { $flag->update(['status'=>$request->action,'reviewed_by'=>\Auth::id()]); return back()->with('success','Fraud flag updated.'); }
    public function auditLogs() { $logs = AuditLog::with('user')->orderByDesc('created_at')->paginate(50); return view('admin.settings.audit', compact('logs')); }
    public function subscriptions() { $plans = SubscriptionPlan::orderBy('sort_order')->get(); return view('admin.subscriptions', compact('plans')); }
    public function updatePlan(Request $request, SubscriptionPlan $plan) { $plan->update($request->except('_token','_method')); return back()->with('success','Plan updated.'); }
}
