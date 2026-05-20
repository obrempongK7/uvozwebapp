@extends('layouts.app')
@section('title','Withdrawals')
@section('content')
<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<h1 class="text-2xl font-bold text-white">Withdrawals</h1>
<a href="{{ route('withdrawal.create') }}" class="btn-primary text-sm py-2 px-5">+ New Withdrawal</a>
</div>
<div class="card p-4 mb-6 flex items-center gap-3" style="border-color:rgba(16,185,129,.3);background:rgba(16,185,129,.05);">
<svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
<div><p class="text-sm font-semibold text-green-400">Available Balance</p><p class="text-xl font-black text-white">${{ number_format($wallet?->usd_balance ?? 0, 2) }}</p></div>
</div>
<div class="card overflow-hidden">
@forelse($withdrawals as $w)
<div class="flex items-center gap-4 p-4 border-b border-dark-border last:border-0">
<div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $w->status==='completed' ? 'bg-green-500/20' : ($w->status==='rejected' ? 'bg-red-500/20' : 'bg-amber-500/20') }}">
@if($w->status==='completed')<svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
@elseif($w->status==='rejected')<svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
@else<svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>@endif
</div>
<div class="flex-1">
<p class="text-sm font-semibold text-white">${{ number_format($w->amount, 2) }} via {{ ucfirst(str_replace('_',' ',$w->method)) }}</p>
<p class="text-xs text-slate-400">{{ $w->created_at->format('M d, Y H:i') }}</p>
</div>
<div class="text-right">
@if($w->status==='completed')<span class="badge badge-green">Paid</span>
@elseif($w->status==='pending')<span class="badge badge-amber">Pending</span>
@elseif($w->status==='approved')<span class="badge badge-purple">Processing</span>
@else<span class="badge" style="background:rgba(239,68,68,.2);color:#FCA5A5;border:1px solid rgba(239,68,68,.3);">{{ ucfirst($w->status) }}</span>@endif
</div>
</div>
@empty
<div class="p-12 text-center">
<svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
<p class="text-slate-400 mb-4">No withdrawals yet</p>
<a href="{{ route('withdrawal.create') }}" class="btn-primary text-sm py-2 px-5">Request First Withdrawal</a>
</div>
@endforelse
</div>
{{ $withdrawals->links() }}
</div>
@endsection
