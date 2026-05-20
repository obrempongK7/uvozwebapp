<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VoicePost;
use App\Models\Withdrawal;
use App\Models\WalletTransaction;
use App\Models\Campaign;
use App\Models\KycVerification;
use App\Models\FraudFlag;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'new_users_today'   => User::whereDate('created_at', today())->count(),
            'new_users_week'    => User::where('created_at', '>=', now()->subWeek())->count(),
            'active_users'      => User::where('last_seen_at', '>=', now()->subDays(30))->count(),
            'total_voice_posts' => VoicePost::count(),
            'pending_withdrawals'=> Withdrawal::where('status','pending')->count(),
            'pending_kyc'       => KycVerification::where('status','pending')->count(),
            'pending_campaigns' => Campaign::where('status','pending')->count(),
            'open_reports'      => Report::where('status','pending')->count(),
            'fraud_flags'       => FraudFlag::where('status','open')->count(),
            'total_points_issued'=> WalletTransaction::where('type','like','%earned%')->sum('points_amount'),
            'total_usd_paid'    => Withdrawal::where('status','completed')->sum('amount'),
        ];

        // Chart data: new users last 30 days
        $userGrowth = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')->orderBy('date')->get();

        // Chart data: points distributed per day
        $pointsChart = WalletTransaction::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(points_amount) as total'))
            ->where('type','points_earned')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')->orderBy('date')->get();

        $recentWithdrawals = Withdrawal::with('user')
            ->where('status','pending')->orderByDesc('created_at')->limit(10)->get();

        $recentUsers = User::orderByDesc('created_at')->limit(10)->get();

        return view('admin.dashboard', compact('stats','userGrowth','pointsChart','recentWithdrawals','recentUsers'));
    }
}
