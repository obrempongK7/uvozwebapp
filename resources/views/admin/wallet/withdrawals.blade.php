@extends('layouts.admin')
@section('page_title','Withdrawals')
@section('content')
<div class="grid sm:grid-cols-2 gap-4 mb-6">
<div class="stat-card"><p class="text-xs text-slate-400 mb-1">Pending</p><p class="text-2xl font-black text-amber-400">${{ number_format($totals['pending'],2) }}</p></div>
<div class="stat-card"><p class="text-xs text-slate-400 mb-1">Total Paid</p><p class="text-2xl font-black text-green-400">${{ number_format($totals['completed'],2) }}</p></div>
</div>
<div class="flex gap-2 mb-4">
<form class="flex gap-2">
<select name="status" class="input-field py-2 text-sm"><option value="">All Status</option>@foreach(['pending','approved','completed','rejected'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select>
<select name="method" class="input-field py-2 text-sm"><option value="">All Methods</option>@foreach(['mobile_money','bank_transfer','paypal','usdt'] as $m)<option value="{{ $m }}" {{ request('method')===$m?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$m)) }}</option>@endforeach</select>
<button type="submit" class="btn-primary py-2">Filter</button>
</form>
</div>
<div class="card overflow-hidden">
<table class="w-full">
<thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">User</th><th class="text-left px-5 py-3">Amount</th><th class="text-left px-5 py-3">Method</th><th class="text-left px-5 py-3">Details</th><th class="text-left px-5 py-3">Date</th><th class="text-left px-5 py-3">Status</th><th class="text-left px-5 py-3">Actions</th></tr></thead>
<tbody>
@forelse($withdrawals as $w)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><div class="flex items-center gap-2"><img src="{{ $w->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover"><span class="text-sm text-white">{{ $w->user->display_name }}</span></div></td>
<td class="px-5 py-3 text-sm font-bold text-green-400">${{ number_format($w->amount,2) }}</td>
<td class="px-5 py-3 text-xs text-slate-400">{{ ucfirst(str_replace('_',' ',$w->method)) }}</td>
<td class="px-5 py-3 text-xs text-slate-400 max-w-32 truncate">{{ implode(', ', array_values(array_filter($w->payment_details??[]))) }}</td>
<td class="px-5 py-3 text-xs text-slate-500">{{ $w->created_at->format('M d, Y') }}</td>
<td class="px-5 py-3">@if($w->status==='completed')<span class="badge-active">Paid</span>@elseif($w->status==='pending')<span class="badge-pending">Pending</span>@elseif($w->status==='rejected')<span class="badge-rejected">Rejected</span>@else<span class="badge-pending">{{ ucfirst($w->status) }}</span>@endif</td>
<td class="px-5 py-3">
@if($w->status==='pending')
<form action="{{ route('admin.wallet.withdrawals.approve',$w) }}" method="POST" class="inline" x-data x-on:submit.prevent="const r=prompt('Transaction reference (optional):');$el.querySelector('[name=reference]').value=r||'';$el.submit()">@csrf<input type="hidden" name="reference" value=""><button class="text-xs text-green-400 hover:text-green-300 mr-3">Approve</button></form>
<form action="{{ route('admin.wallet.withdrawals.reject',$w) }}" method="POST" class="inline" x-data x-on:submit.prevent="const r=prompt('Rejection reason:');if(r){$el.querySelector('[name=reason]').value=r;$el.submit()}">@csrf<input type="hidden" name="reason" value=""><button class="text-xs text-red-400 hover:text-red-300">Reject</button></form>
@endif
</td>
</tr>
@empty<tr><td colspan="7" class="px-5 py-8 text-center text-slate-400">No withdrawals found</td></tr>@endforelse
</tbody>
</table>
<div class="px-5 py-4 border-t border-dark-border">{{ $withdrawals->links() }}</div>
</div>
@endsection
