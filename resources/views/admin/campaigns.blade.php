@extends('layouts.admin')
@section('page_title','Campaigns')
@section('content')
<div class="card overflow-hidden">
<table class="w-full"><thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">Campaign</th><th class="text-left px-5 py-3">Advertiser</th><th class="text-left px-5 py-3">Budget</th><th class="text-left px-5 py-3">Actions Completed</th><th class="text-left px-5 py-3">Status</th><th class="text-left px-5 py-3">Actions</th></tr></thead>
<tbody>
@forelse($campaigns as $c)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><p class="text-sm font-medium text-white">{{ $c->title }}</p><span class="text-xs text-slate-500">{{ ucfirst(str_replace('_',' ',$c->type)) }}</span></td>
<td class="px-5 py-3 text-sm text-slate-400">{{ $c->advertiser->display_name }}</td>
<td class="px-5 py-3 text-sm font-semibold text-green-400">${{ number_format($c->budget,2) }}</td>
<td class="px-5 py-3 text-sm text-slate-400">{{ number_format($c->completed_actions) }}</td>
<td class="px-5 py-3">@if($c->status==='active')<span class="badge-active">Active</span>@elseif($c->status==='pending')<span class="badge-pending">Pending</span>@elseif($c->status==='rejected')<span class="badge-rejected">Rejected</span>@else<span class="text-xs text-slate-500">{{ ucfirst($c->status) }}</span>@endif</td>
<td class="px-5 py-3">
<div class="flex gap-2">
@if($c->status==='pending')
<form action="{{ route('admin.campaigns.approve',$c) }}" method="POST" class="inline">@csrf<button class="text-xs text-green-400 hover:text-green-300">Approve</button></form>
<form action="{{ route('admin.campaigns.reject',$c) }}" method="POST" class="inline" x-data x-on:submit.prevent="const r=prompt('Rejection reason:');if(r){$el.querySelector('[name=reason]').value=r;$el.submit()}">@csrf<input type="hidden" name="reason" value=""><button class="text-xs text-red-400 hover:text-red-300">Reject</button></form>
@endif
</div>
</td>
</tr>
@empty<tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">No campaigns</td></tr>@endforelse
</tbody></table>
<div class="px-5 py-4 border-t border-dark-border">{{ $campaigns->links() }}</div>
</div>
@endsection
