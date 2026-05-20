<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller {
    public function index() {
        $user = Auth::user();
        $referrals = Referral::with('referred')->where('referrer_id',$user->id)->orderByDesc('created_at')->paginate(20);
        $totalEarned = $referrals->sum('total_points_earned');
        $referralLink = route('register', ['ref' => $user->referral_code]);
        return view('user.referrals', compact('referrals','totalEarned','referralLink','user'));
    }
}
