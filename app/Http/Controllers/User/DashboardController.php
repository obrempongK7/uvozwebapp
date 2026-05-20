<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\VoicePost;
use App\Models\StatusPost;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user()->load(['profile', 'wallet']);
        $wallet = $user->wallet;

        $recentVoicePosts = VoicePost::where('user_id', $user->id)
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->limit(5)->get();

        $recentTransactions = WalletTransaction::where('user_id', $user->id)
            ->orderByDesc('created_at')->limit(10)->get();

        $followersCount  = $user->followers()->count();
        $followingCount  = $user->following()->count();
        $totalVoicePosts = VoicePost::where('user_id', $user->id)->count();
        $totalPlays      = VoicePost::where('user_id', $user->id)->sum('plays_count');

        $streak = \App\Models\UserStreak::firstOrCreate(['user_id' => $user->id]);

        // Calculate daily progress
        $dailyCap   = $user->dailyPointsCap();
        $todayEarned = $wallet?->today_points_earned ?? 0;
        $dailyProgress = $dailyCap > 0 ? min(100, round($todayEarned / $dailyCap * 100)) : 0;

        return view('user.dashboard', compact(
            'user', 'wallet', 'recentVoicePosts', 'recentTransactions',
            'followersCount', 'followingCount', 'totalVoicePosts', 'totalPlays',
            'streak', 'dailyCap', 'todayEarned', 'dailyProgress'
        ));
    }
}
