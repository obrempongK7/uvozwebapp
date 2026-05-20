<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\RewardCatalog;
use App\Models\RewardRedemption;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller {
    public function __construct(private WalletService $ws) {}
    public function index() {
        $user = Auth::user();
        $rewards = RewardCatalog::where('is_active',true)->whereNull('available_countries')->orWhereJsonContains('available_countries',$user->country)->get()->groupBy('category');
        $wallet = $user->wallet;
        $redemptions = RewardRedemption::where('user_id',$user->id)->with('reward')->orderByDesc('created_at')->limit(10)->get();
        return view('user.rewards', compact('rewards','wallet','redemptions'));
    }
    public function redeem(Request $request, RewardCatalog $reward) {
        $user = Auth::user();
        $wallet = $this->ws->getOrCreate($user);
        if ($wallet->points_balance < $reward->points_required) return back()->withErrors(['points'=>'Insufficient points.']);
        if ($reward->stock_quantity !== null && $reward->stock_quantity <= 0) return back()->withErrors(['stock'=>'Out of stock.']);
        $this->ws->deductPoints($user,$reward->points_required,"Redeemed: {$reward->title}",$reward);
        RewardRedemption::create(['user_id'=>$user->id,'reward_id'=>$reward->id,'wallet_id'=>$wallet->id,'points_spent'=>$reward->points_required,'usd_value'=>$reward->usd_value,'status'=>'pending']);
        if ($reward->stock_quantity !== null) $reward->decrement('stock_quantity');
        return back()->with('success','Redemption request submitted!');
    }
}
