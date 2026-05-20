@extends('layouts.app')
@section('title','My Campaigns')
@section('content')
<div class="max-w-4xl mx-auto">
<div class="flex items-center justify-between mb-6">
<h1 class="text-2xl font-bold text-white">My Campaigns</h1>
<a href="{{ route('campaign.create') }}" class="btn-primary text-sm py-2 px-5">+ New Campaign</a>
</div>
<div class="card overflow-hidden">
<table class="w-full"><thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">Campaign</th><th class="text-left px-5 py-3">Budget</th><th class="text-left px-5 py-3">Spent</th><th class="text-left px-5 py-3">Completions</th><th class="text-left px-5 py-3">Status</th><th class="text-left px-5 py-3">Actions</th></tr></thead>
<tbody>
@forelse($campaigns as $c)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><p class="text-sm font-medium text-white">{{ $c->title }}</p><span class="text-xs text-slate-500">{{ ucfirst(str_replace('_',' ',$c->type)) }}</span></td>
<td class="px-5 py-3 text-sm font-semibold text-green-400">${{ number_format($c->budget, 2) }}</td>
<td class="px-5 py-3 text-sm text-slate-400">${{ number_format($c->spent, 2) }}</td>
<td class="px-5 py-3 text-sm text-slate-400">{{ number_format($c->completed_actions) }}</td>
<td class="px-5 py-3">@if($c->status==='active')<span class="badge-active">Active</span>@elseif($c->status==='pending')<span class="badge-pending">Pending Review</span>@elseif($c->status==='rejected')<span class="badge-rejected">Rejected</span>@else<span class="text-xs text-slate-500">{{ ucfirst($c->status) }}</span>@endif</td>
<td class="px-5 py-3"><a href="{{ route('campaign.analytics', $c) }}" class="text-xs text-purple-400 hover:text-purple-300">Analytics</a></td>
</tr>
@empty<tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">No campaigns yet</td></tr>@endforelse
</tbody></table>
<div class="px-5 py-4 border-t border-dark-border">{{ $campaigns->links() }}</div>
</div>
</div>
@endsection
