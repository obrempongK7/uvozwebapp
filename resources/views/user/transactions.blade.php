@extends('layouts.app')
@section('title','Transactions')
@section('content')
<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<h1 class="text-2xl font-bold text-white">Transaction History</h1>
<a href="{{ route('wallet.index') }}" class="btn-secondary text-sm py-2 px-4">← Wallet</a>
</div>
<div class="card overflow-hidden">
@forelse($transactions as $tx)
<div class="flex items-center gap-4 p-4 border-b border-dark-border last:border-0">
<div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ in_array($tx->type,['points_earned','usd_earned','referral_bonus','campaign_reward']) ? 'bg-green-500/15' : 'bg-red-500/15' }}">
@if(in_array($tx->type,['points_earned','usd_earned','referral_bonus','campaign_reward']))<svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
@else<svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>@endif
</div>
<div class="flex-1 min-w-0">
<p class="text-sm text-white font-medium truncate">{{ $tx->description }}</p>
<p class="text-xs text-slate-500">{{ $tx->created_at->format('M d, Y H:i') }} · {{ ucfirst(str_replace('_',' ',$tx->type)) }}</p>
</div>
<div class="text-right flex-shrink-0">
@if($tx->points_amount)<p class="text-sm font-semibold {{ in_array($tx->type,['points_earned','referral_bonus','campaign_reward']) ? 'text-green-400':'text-red-400' }}">{{ in_array($tx->type,['points_earned','referral_bonus','campaign_reward'])?'+':'-' }}{{ number_format($tx->points_amount) }} pts</p>@endif
@if($tx->usd_amount > 0)<p class="text-xs text-slate-400">${{ number_format($tx->usd_amount,4) }}</p>@endif
</div>
</div>
@empty
<div class="p-12 text-center"><p class="text-slate-400">No transactions yet.</p></div>
@endforelse
</div>
{{ $transactions->links() }}
</div>
@endsection
