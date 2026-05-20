<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller {
    public function __construct(private WalletService $walletService) {}
    public function index() {
        $user = Auth::user()->load(['wallet']);
        $wallet = $user->wallet ?? $this->walletService->getOrCreate($user);
        $transactions = \App\Models\WalletTransaction::where('user_id',$user->id)->orderByDesc('created_at')->paginate(20);
        return view('user.wallet', compact('user','wallet','transactions'));
    }
    public function transactions() {
        $transactions = \App\Models\WalletTransaction::where('user_id',Auth::id())->orderByDesc('created_at')->paginate(30);
        return view('user.transactions', compact('transactions'));
    }
    public function plans() {
        $plans = SubscriptionPlan::where('is_active',true)->orderBy('sort_order')->get();
        $current = Auth::user()->subscription;
        return view('user.plans', compact('plans','current'));
    }
    public function subscribe(Request $request, SubscriptionPlan $plan) {
        // Redirect to payment gateway - simplified
        return redirect()->route('subscribe.plans')->with('info','Payment integration coming soon.');
    }
    public function cancelSubscription() {
        $sub = Auth::user()->subscription;
        $sub?->update(['status'=>'cancelled','cancelled_at'=>now()]);
        return back()->with('success','Subscription cancelled.');
    }
    public function subscribeSuccess() { return view('user.subscribe-success'); }
}
