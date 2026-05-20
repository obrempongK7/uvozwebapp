@extends('layouts.app')
@section('title','Withdrawal Details')
@section('content')
<div class="max-w-xl mx-auto">
<a href="{{ route('withdrawal.index') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-white mb-6">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
Back to Withdrawals
</a>
<div class="card p-6">
<div class="flex items-center gap-4 mb-6">
<div class="w-14 h-14 gradient-bg rounded-2xl flex items-center justify-center text-2xl">💸</div>
<div>
<p class="text-2xl font-black text-white">${{ number_format($withdrawal->amount, 2) }}</p>
<p class="text-sm text-slate-400">{{ ucfirst(str_replace('_',' ',$withdrawal->method)) }}</p>
</div>
<div class="ml-auto">
@if($withdrawal->status==='completed')<span class="badge badge-green">Paid ✓</span>
@elseif($withdrawal->status==='pending')<span class="badge badge-amber">Pending</span>
@elseif($withdrawal->status==='approved')<span class="badge badge-purple">Processing</span>
@else<span class="badge" style="background:rgba(239,68,68,.2);color:#FCA5A5;">{{ ucfirst($withdrawal->status) }}</span>@endif
</div>
</div>
<div class="space-y-3">
<div class="flex justify-between py-2 border-b border-dark-border"><span class="text-sm text-slate-400">Amount</span><span class="text-sm font-semibold text-white">${{ number_format($withdrawal->amount, 2) }}</span></div>
<div class="flex justify-between py-2 border-b border-dark-border"><span class="text-sm text-slate-400">Method</span><span class="text-sm text-white">{{ ucfirst(str_replace('_',' ',$withdrawal->method)) }}</span></div>
<div class="flex justify-between py-2 border-b border-dark-border"><span class="text-sm text-slate-400">Status</span><span class="text-sm text-white">{{ ucfirst($withdrawal->status) }}</span></div>
<div class="flex justify-between py-2 border-b border-dark-border"><span class="text-sm text-slate-400">Submitted</span><span class="text-sm text-white">{{ $withdrawal->created_at->format('M d, Y H:i') }}</span></div>
@if($withdrawal->processed_at)<div class="flex justify-between py-2 border-b border-dark-border"><span class="text-sm text-slate-400">Processed</span><span class="text-sm text-white">{{ $withdrawal->processed_at->format('M d, Y H:i') }}</span></div>@endif
@if($withdrawal->transaction_reference)<div class="flex justify-between py-2 border-b border-dark-border"><span class="text-sm text-slate-400">Reference</span><span class="text-sm font-mono text-purple-400">{{ $withdrawal->transaction_reference }}</span></div>@endif
@if($withdrawal->admin_notes && $withdrawal->status==='rejected')<div class="p-3 rounded-xl bg-red-500/10 border border-red-500/20 mt-2"><p class="text-xs text-red-400"><strong>Rejection reason:</strong> {{ $withdrawal->admin_notes }}</p></div>@endif
</div>
</div>
</div>
@endsection
