@extends('layouts.app')
@section('title','My Wallet')
@section('content')
<div class="mb-6"><h1 class="text-2xl font-bold text-white">My Wallet</h1><p class="text-slate-400 text-sm mt-1">Manage your earnings and balance</p></div>
<div class="grid lg:grid-cols-3 gap-5 mb-8">
<!-- Points Balance -->
<div class="card p-6" style="background:linear-gradient(135deg,rgba(139,92,246,.15),rgba(6,182,212,.1));border-color:rgba(139,92,246,.3);">
<div class="flex items-center gap-3 mb-4">
<div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center">
<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
</div>
<div><p class="text-xs text-slate-400 uppercase tracking-wider">Points Balance</p></div>
</div>
<p class="text-4xl font-black text-white mb-1">{{ number_format($wallet?->points_balance ?? 0) }}</p>
<p class="text-sm text-purple-300">≈ ${{ number_format(($wallet?->points_balance ?? 0)/1000,2) }} USD</p>
</div>
<!-- USD Balance -->
<div class="card p-6" style="background:linear-gradient(135deg,rgba(16,185,129,.12),rgba(16,185,129,.05));border-color:rgba(16,185,129,.3);">
<div class="flex items-center gap-3 mb-4">
<div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(16,185,129,.2)">
<svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
</div>
<p class="text-xs text-slate-400 uppercase tracking-wider">Available USD</p>
</div>
<p class="text-4xl font-black text-green-400 mb-1">${{ number_format($wallet?->usd_balance ?? 0, 2) }}</p>
<a href="{{ route('withdrawal.create') }}" class="text-sm text-green-300 hover:text-green-200">Withdraw funds →</a>
</div>
<!-- Total Earned -->
<div class="card p-6">
<div class="flex items-center gap-3 mb-4">
<div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(16,185,129,.2)">
<svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
</div>
<p class="text-xs text-slate-400 uppercase tracking-wider">All-Time Earned</p>
</div>
<p class="text-4xl font-black text-amber-400 mb-1">${{ number_format($wallet?->total_earned_usd ?? 0, 2) }}</p>
<p class="text-sm text-slate-400">Total withdrawn: ${{ number_format($wallet?->total_withdrawn_usd ?? 0, 2) }}</p>
</div>
</div>
<!-- Quick Actions -->
<div class="grid sm:grid-cols-3 gap-4 mb-8">
<a href="{{ route('withdrawal.create') }}" class="card p-5 flex items-center gap-4 card-hover">
<div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:rgba(16,185,129,.2)"><svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div>
<div><p class="font-semibold text-white">Withdraw</p><p class="text-xs text-slate-400">Cash out earnings</p></div>
</a>
<a href="{{ route('wallet.rewards') }}" class="card p-5 flex items-center gap-4 card-hover">
<div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:rgba(139,92,246,.2)"><svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"/></svg></div>
<div><p class="font-semibold text-white">Rewards</p><p class="text-xs text-slate-400">Redeem for prizes</p></div>
</a>
<a href="{{ route('campaign.index') }}" class="card p-5 flex items-center gap-4 card-hover">
<div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:rgba(16,185,129,.2)"><svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg></div>
<div><p class="font-semibold text-white">Earn More</p><p class="text-xs text-slate-400">Complete campaigns</p></div>
</a>
</div>
<!-- Recent Transactions -->
<div class="card p-5">
<div class="flex items-center justify-between mb-4">
<h3 class="font-semibold text-white">Transaction History</h3>
<a href="{{ route('wallet.transactions') }}" class="text-xs text-purple-400 hover:text-purple-300">View all</a>
</div>
<div class="space-y-2">
@forelse($transactions as $tx)
<div class="flex items-center gap-3 py-3 border-b border-dark-border last:border-0">
<div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ in_array($tx->type,['points_earned','usd_earned','referral_bonus','campaign_reward']) ? 'bg-green-500/15' : 'bg-red-500/15' }}">
@if(in_array($tx->type,['points_earned','usd_earned','referral_bonus','campaign_reward']))<svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
@else<svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>@endif
</div>
<div class="flex-1 min-w-0">
<p class="text-sm text-white font-medium truncate">{{ $tx->description }}</p>
<p class="text-xs text-slate-500">{{ $tx->created_at->diffForHumans() }} · {{ ucfirst(str_replace('_',' ',$tx->type)) }}</p>
</div>
<div class="text-right flex-shrink-0">
@if($tx->points_amount)<p class="text-sm font-semibold {{ in_array($tx->type,['points_earned','referral_bonus','campaign_reward']) ? 'text-green-400' : 'text-red-400' }}">{{ in_array($tx->type,['points_earned','referral_bonus','campaign_reward'])?'+':'-' }}{{ number_format($tx->points_amount) }} pts</p>@endif
@if($tx->usd_amount > 0)<p class="text-xs text-slate-400">${{ number_format($tx->usd_amount,4) }}</p>@endif
</div>
</div>
@empty
<div class="text-center py-8"><p class="text-slate-400">No transactions yet. Start creating and earning!</p></div>
@endforelse
</div>
{{ $transactions->links() }}
</div>
@endsection
