@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $user->display_name }} 👋</h1>
            <p class="text-slate-400 mt-1">Here's what's happening with your account today.</p>
        </div>
        <a href="{{ route('voice.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
            New Voice Post
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center" style="background:rgba(124,58,237,.2)">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <span class="text-xs text-green-400">Today</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($todayEarned) }}</p>
            <p class="text-xs text-slate-400 mt-1">Points earned</p>
            <div class="mt-3">
                <div class="flex justify-between text-xs text-slate-500 mb-1">
                    <span>{{ $dailyProgress }}% of daily cap</span>
                    <span>{{ number_format($dailyCap) }} cap</span>
                </div>
                <div class="h-1.5 bg-dark-border rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500" style="background:linear-gradient(90deg,#7C3AED,#EC4899);width:{{ $dailyProgress }}%"></div>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center" style="background:rgba(16,185,129,.2)">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-slate-400">Balance</span>
            </div>
            <p class="text-2xl font-bold text-white">${{ number_format($wallet?->usd_balance ?? 0, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ number_format($wallet?->points_balance ?? 0) }} points</p>
            <a href="{{ route('wallet.index') }}" class="mt-3 text-xs text-purple-400 hover:text-purple-300 flex items-center gap-1">View wallet <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center" style="background:rgba(236,72,153,.2)">
                    <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs text-slate-400">Network</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($followersCount) }}</p>
            <p class="text-xs text-slate-400 mt-1">Followers</p>
            <p class="text-xs text-slate-500 mt-1">Following {{ number_format($followingCount) }}</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center" style="background:rgba(245,158,11,.2)">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                </div>
                <span class="text-xs text-slate-400">Content</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($totalVoicePosts) }}</p>
            <p class="text-xs text-slate-400 mt-1">Voice posts</p>
            <p class="text-xs text-slate-500 mt-1">{{ number_format($totalPlays) }} total plays</p>
        </div>
    </div>

    <!-- Streak & Subscription -->
    <div class="grid lg:grid-cols-3 gap-4">
        <!-- Daily Streak -->
        <div class="card p-5">
            <h3 class="text-sm font-semibold text-slate-300 mb-3">🔥 Daily Streak</h3>
            <div class="flex items-center gap-4">
                <div class="text-center">
                    <p class="text-3xl font-black text-amber-400">{{ $streak->current_streak }}</p>
                    <p class="text-xs text-slate-500">Current</p>
                </div>
                <div class="flex-1">
                    <div class="flex gap-1 flex-wrap">
                        @for($i = 1; $i <= 7; $i++)
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold {{ $i <= ($streak->current_streak % 7 ?: 7) ? 'gradient-bg text-white' : 'bg-dark-border text-slate-600' }}">
                            {{ ['M','T','W','T','F','S','S'][$i-1] }}
                        </div>
                        @endfor
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Best: {{ $streak->longest_streak }} days</p>
                </div>
            </div>
        </div>

        <!-- Subscription Status -->
        <div class="card p-5">
            <h3 class="text-sm font-semibold text-slate-300 mb-3">📋 Your Plan</h3>
            @php $sub = $user->subscription; $plan = $sub?->plan; @endphp
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr($plan?->name ?? 'F', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-white">{{ $plan?->name ?? 'Free Plan' }}</p>
                    <p class="text-xs text-slate-400">{{ number_format($plan?->daily_points_cap ?? 1000) }} pts/day cap</p>
                </div>
            </div>
            @if(!$plan || $plan->isFree())
            <a href="{{ route('subscribe.plans') }}" class="btn-primary text-xs py-2 w-full justify-center">Upgrade for more earnings</a>
            @else
            <div class="flex items-center gap-2">
                <span class="badge badge-green">Active</span>
                <span class="text-xs text-slate-400">+{{ $plan->earning_boost_percent }}% boost</span>
            </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="card p-5">
            <h3 class="text-sm font-semibold text-slate-300 mb-3">⚡ Quick Actions</h3>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('voice.create') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-dark-hover transition-colors border border-dark-border text-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    <span class="text-xs text-slate-300">Record</span>
                </a>
                <a href="{{ route('withdrawal.create') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-dark-hover transition-colors border border-dark-border text-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <span class="text-xs text-slate-300">Withdraw</span>
                </a>
                <a href="{{ route('campaign.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-dark-hover transition-colors border border-dark-border text-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6"/></svg>
                    <span class="text-xs text-slate-300">Earn More</span>
                </a>
                <a href="{{ route('referrals.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-dark-hover transition-colors border border-dark-border text-center">
                    <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/></svg>
                    <span class="text-xs text-slate-300">Refer</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Posts & Transactions -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Recent Voice Posts -->
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-white">Recent Voice Posts</h3>
                <a href="{{ route('voice.index') }}" class="text-xs text-purple-400 hover:text-purple-300">View all</a>
            </div>
            @forelse($recentVoicePosts as $post)
            <div class="flex items-center gap-3 py-3 border-b border-dark-border last:border-0">
                <div class="w-10 h-10 rounded-xl bg-dark-border flex items-center justify-center flex-shrink-0">
                    @if($post->cover_image)
                    <img src="{{ $post->cover_url }}" class="w-full h-full object-cover rounded-xl">
                    @else
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('voice.show', $post) }}" class="text-sm font-medium text-white hover:text-purple-400 truncate block">{{ $post->title }}</a>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs text-slate-500">{{ number_format($post->plays_count) }} plays</span>
                        <span class="text-xs text-slate-500">{{ number_format($post->likes_count) }} likes</span>
                        <span class="text-xs text-slate-500">{{ $post->published_at?->diffForHumans() }}</span>
                    </div>
                </div>
                <button onclick="playVoice('{{ $post->audio_url }}','{{ addslashes($post->title) }}','{{ addslashes($user->display_name) }}','{{ $post->cover_url }}')"
                    class="w-8 h-8 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                <p class="text-slate-400 text-sm mb-3">No voice posts yet</p>
                <a href="{{ route('voice.create') }}" class="btn-primary text-sm py-2 px-4 inline-flex">Create your first post</a>
            </div>
            @endforelse
        </div>

        <!-- Recent Transactions -->
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-white">Recent Earnings</h3>
                <a href="{{ route('wallet.transactions') }}" class="text-xs text-purple-400 hover:text-purple-300">View all</a>
            </div>
            @forelse($recentTransactions as $tx)
            <div class="flex items-center gap-3 py-2.5 border-b border-dark-border last:border-0">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ str_contains($tx->type,'earned') || str_contains($tx->type,'bonus') ? 'bg-green-500/20' : 'bg-red-500/20' }}">
                    @if(str_contains($tx->type,'earned') || str_contains($tx->type,'bonus'))
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    @else
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-white truncate">{{ $tx->description }}</p>
                    <p class="text-xs text-slate-500">{{ $tx->created_at->diffForHumans() }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold {{ str_contains($tx->type,'earned') || str_contains($tx->type,'bonus') ? 'text-green-400' : 'text-red-400' }}">
                        {{ str_contains($tx->type,'earned') || str_contains($tx->type,'bonus') ? '+' : '-' }}{{ number_format($tx->points_amount) }} pts
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <p class="text-slate-400 text-sm">No transactions yet. Start creating content!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
