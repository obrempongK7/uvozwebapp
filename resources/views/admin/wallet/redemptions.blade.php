@extends('layouts.admin')
@section('page_title','Reward Redemptions')
@section('content')
<div class="card overflow-hidden">
<table class="w-full"><thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">User</th><th class="text-left px-5 py-3">Reward</th><th class="text-left px-5 py-3">Points</th><th class="text-left px-5 py-3">Status</th><th class="text-left px-5 py-3">Date</th><th class="text-left px-5 py-3">Actions</th></tr></thead>
<tbody>
@forelse($redemptions as $r)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><div class="flex items-center gap-2"><img src="{{ $r->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover"><span class="text-sm text-white">{{ $r->user->display_name }}</span></div></td>
<td class="px-5 py-3 text-sm text-white">{{ $r->reward->title }}</td>
<td class="px-5 py-3 text-sm font-semibold text-amber-400">{{ number_format($r->points_spent) }}</td>
<td class="px-5 py-3">@if($r->status==='fulfilled')<span class="badge-active">Fulfilled</span>@elseif($r->status==='pending')<span class="badge-pending">Pending</span>@else<span class="text-xs text-slate-500">{{ ucfirst($r->status) }}</span>@endif</td>
<td class="px-5 py-3 text-xs text-slate-500">{{ $r->created_at->format('M d, Y') }}</td>
<td class="px-5 py-3">@if($r->status==='pending')<form action="{{ route('admin.wallet.redemptions.fulfill',$r) }}" method="POST" class="inline">@csrf<button class="text-xs text-green-400 hover:text-green-300">Fulfill</button></form>@endif</td>
</tr>
@empty<tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">No redemptions</td></tr>@endforelse
</tbody></table>
<div class="px-5 py-4 border-t border-dark-border">{{ $redemptions->links() }}</div>
</div>
@endsection
