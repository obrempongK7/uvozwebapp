<?php
namespace App\Http\Controllers;
use App\Models\SiteSetting;
use App\Models\SubscriptionPlan;
use App\Models\BlogPost;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class LandingController extends Controller {
    public function index() {
        $plans = SubscriptionPlan::where('is_active',true)->orderBy('sort_order')->get();
        $recentPayouts = Withdrawal::with('user')->where('status','completed')->orderByDesc('processed_at')->limit(10)->get();
        $blogPosts = BlogPost::where('status','published')->orderByDesc('published_at')->limit(3)->get();
        return view('landing.home', compact('plans','recentPayouts','blogPosts'));
    }
    public function about() { return view('landing.about'); }
    public function howItWorks() { return view('landing.how-it-works'); }
    public function pricing() { $plans = SubscriptionPlan::where('is_active',true)->orderBy('sort_order')->get(); return view('landing.pricing', compact('plans')); }
    public function features() { return view('landing.features'); }
    public function faq() { return view('landing.faq'); }
    public function contact() { return view('landing.contact'); }
    public function sendContact(Request $request) { $request->validate(['name'=>['required'],'email'=>['required','email'],'message'=>['required','string']]); return back()->with('success','Message sent! We\'ll get back to you soon.'); }
    public function privacy() { return view('landing.privacy'); }
    public function terms() { return view('landing.terms'); }
}
