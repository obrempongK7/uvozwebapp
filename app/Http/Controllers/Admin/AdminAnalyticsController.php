<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VoicePost;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller {
    public function index() {
        $dailyUsers = User::select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as c'))->where('created_at','>=',now()->subDays(30))->groupBy('d')->orderBy('d')->get();
        $revenueChart = UserSubscription::select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(amount) as t'))->where('created_at','>=',now()->subDays(30))->groupBy('d')->orderBy('d')->get();
        $pointsChart = WalletTransaction::select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(points_amount) as t'))->where('type','points_earned')->where('created_at','>=',now()->subDays(30))->groupBy('d')->orderBy('d')->get();
        $topContent = VoicePost::with('user')->orderByDesc('engagement_score')->limit(10)->get();
        $retentionData = ['dau'=>User::where('last_seen_at','>=',now()->subDay())->count(),'mau'=>User::where('last_seen_at','>=',now()->subDays(30))->count()];
        return view('admin.analytics', compact('dailyUsers','revenueChart','pointsChart','topContent','retentionData'));
    }
}
