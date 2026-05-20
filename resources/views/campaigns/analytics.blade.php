@extends('layouts.app')
@section('title','Campaign Analytics')
@section('content')
<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<h1 class="text-2xl font-bold text-white">{{ $campaign->title }}</h1>
<a href="{{ route('campaign.mine') }}" class="btn-secondary text-sm py-2 px-4">← My Campaigns</a>
</div>
<div class="grid sm:grid-cols-3 gap-4 mb-6">
<div class="card p-5"><p class="text-xs text-slate-400 mb-1">Completions</p><p class="text-3xl font-black text-white">{{ number_format($stats['total']) }}</p></div>
<div class="card p-5"><p class="text-xs text-slate-400 mb-1">Budget Used</p><p class="text-3xl font-black text-green-400">${{ number_format($stats['budget_used'], 2) }}</p></div>
<div class="card p-5"><p class="text-xs text-slate-400 mb-1">Remaining</p><p class="text-3xl font-black text-amber-400">${{ number_format($stats['remaining'], 2) }}</p></div>
</div>
<div class="card p-5">
<h3 class="font-semibold text-white mb-4">Campaign Details</h3>
<div class="space-y-3">
<div class="flex justify-between py-2 border-b border-dark-border"><span class="text-sm text-slate-400">Type</span><span class="text-sm text-white">{{ ucfirst(str_replace('_',' ',$campaign->type)) }}</span></div>
<div class="flex justify-between py-2 border-b border-dark-border"><span class="text-sm text-slate-400">Points per action</span><span class="text-sm font-bold text-amber-400">{{ number_format($campaign->points_per_action) }}</span></div>
<div class="flex justify-between py-2 border-b border-dark-border"><span class="text-sm text-slate-400">Total budget</span><span class="text-sm text-white">${{ number_format($campaign->budget, 2) }}</span></div>
<div class="flex justify-between py-2"><span class="text-sm text-slate-400">Status</span><span class="text-sm text-white">{{ ucfirst($campaign->status) }}</span></div>
</div>
</div>
</div>
@endsection
