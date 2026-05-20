@extends('layouts.admin')
@section('page_title','User: '.$user->display_name)
@section('content')
<div class="grid lg:grid-cols-3 gap-6">
<div class="lg:col-span-1 space-y-4">
<div class="card p-6 text-center">
<img src="{{ $user->avatar_url }}" class="w-20 h-20 rounded-full object-cover mx-auto mb-3">
<h2 class="text-xl font-bold text-white">{{ $user->display_name }}</h2>
<p class="text-slate-400 text-sm">@{{ $user->username }}</p>
<p class="text-xs text-slate-500 mt-1">{{ $user->email }}</p>
<div class="flex justify-center gap-2 mt-3">
@if($user->is_verified)<span class="badge-active">Verified</span>@endif
@if($user->is_banned)<span class="badge-rejected">Banned</span>@endif
<span class="badge-pending">{{ ucfirst($user->role) }}</span>
</div>
</div>
<div class="card p-5">
<h3 class="font-semibold text-white mb-3">Wallet</h3>
<div class="space-y-2">
<div class="flex justify-between"><span class="text-xs text-slate-400">Points</span><span class="text-sm font-semibold text-amber-400">{{ number_format($user->wallet?->points_balance ?? 0) }}</span></div>
<div class="flex justify-between"><span class="text-xs text-slate-400">USD Balance</span><span class="text-sm font-semibold text-green-400">${{ number_format($user->wallet?->usd_balance ?? 0, 2) }}</span></div>
<div class="flex justify-between"><span class="text-xs text-slate-400">Total Earned</span><span class="text-sm text-slate-300">${{ number_format($user->wallet?->total_earned_usd ?? 0, 2) }}</span></div>
</div>
</div>
<div class="card p-5 space-y-3">
<h3 class="font-semibold text-white mb-2">Admin Actions</h3>
<form action="{{ route('admin.users.verify',$user) }}" method="POST">@csrf<button class="w-full btn-primary py-2 text-sm justify-center">{{ $user->is_verified?'Remove Verification':'Verify User' }}</button></form>
<form action="{{ route('admin.users.impersonate',$user) }}" method="POST">@csrf<button class="w-full btn-secondary py-2 text-sm justify-center">Impersonate</button></form>
@if(!$user->is_banned)
<form action="{{ route('admin.users.ban',$user) }}" method="POST" x-data x-on:submit.prevent="const r=prompt('Ban reason:');if(r){$el.querySelector('[name=reason]').value=r;$el.submit()}">@csrf<input type="hidden" name="reason" value=""><button class="w-full py-2 rounded-xl bg-red-500/15 text-red-400 text-sm font-medium hover:bg-red-500/25">Ban User</button></form>
@else
<form action="{{ route('admin.users.unban',$user) }}" method="POST">@csrf<button class="w-full py-2 rounded-xl bg-blue-500/15 text-blue-400 text-sm font-medium">Unban User</button></form>
@endif
</div>
</div>
<div class="lg:col-span-2 space-y-5">
<div class="card p-5">
<h3 class="font-semibold text-white mb-4">Recent Transactions</h3>
<div class="space-y-2">
@forelse($transactions as $tx)
<div class="flex items-center gap-3 py-2 border-b border-dark-border last:border-0">
<div class="flex-1"><p class="text-sm text-white">{{ $tx->description }}</p><p class="text-xs text-slate-500">{{ $tx->created_at->format('M d H:i') }}</p></div>
<span class="text-sm font-semibold {{ str_contains($tx->type,'earned')||str_contains($tx->type,'bonus') ? 'text-green-400':'text-red-400' }}">{{ number_format($tx->points_amount) }} pts</span>
</div>
@empty<p class="text-slate-400 text-sm py-3 text-center">No transactions</p>@endforelse
</div>
</div>
<div class="card p-5">
<h3 class="font-semibold text-white mb-4">Withdrawals</h3>
<div class="space-y-2">
@forelse($withdrawals as $w)
<div class="flex items-center justify-between py-2 border-b border-dark-border last:border-0">
<div><p class="text-sm text-white">${{ number_format($w->amount,2) }} via {{ ucfirst(str_replace('_',' ',$w->method)) }}</p><p class="text-xs text-slate-500">{{ $w->created_at->format('M d Y') }}</p></div>
@if($w->status==='completed')<span class="badge-active text-xs">Paid</span>@elseif($w->status==='pending')<span class="badge-pending text-xs">Pending</span>@else<span class="badge-rejected text-xs">{{ ucfirst($w->status) }}</span>@endif
</div>
@empty<p class="text-slate-400 text-sm py-3 text-center">No withdrawals</p>@endforelse
</div>
</div>
</div>
</div>
@endsection
