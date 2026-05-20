@extends('layouts.app')
@section('title', $campaign->title)
@section('content')
<div class="max-w-2xl mx-auto">
<a href="{{ route('campaign.index') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-white mb-6">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Campaigns
</a>
<div class="card p-6">
@if($campaign->cover_image)<img src="{{ asset('storage/'.$campaign->cover_image) }}" class="w-full h-48 object-cover rounded-2xl mb-5">@endif
<div class="flex items-start justify-between mb-4">
<div>
<h1 class="text-2xl font-bold text-white mb-1">{{ $campaign->title }}</h1>
<p class="text-sm text-slate-400">by {{ $campaign->advertiser->display_name }}</p>
</div>
<div class="text-right ml-4">
<p class="text-3xl font-black text-amber-400">+{{ number_format($campaign->points_per_action) }}</p>
<p class="text-xs text-slate-500">points reward</p>
</div>
</div>
@if($campaign->description)<p class="text-slate-300 leading-relaxed mb-6">{{ $campaign->description }}</p>@endif
<div class="grid sm:grid-cols-2 gap-4 mb-6">
<div class="p-3 rounded-xl bg-dark-border"><p class="text-xs text-slate-400">Type</p><p class="text-sm font-medium text-white">{{ ucfirst(str_replace('_',' ',$campaign->type)) }}</p></div>
<div class="p-3 rounded-xl bg-dark-border"><p class="text-xs text-slate-400">Completions</p><p class="text-sm font-medium text-white">{{ number_format($campaign->completed_actions) }}</p></div>
</div>
@if($campaign->target_url)
<div class="p-4 rounded-2xl bg-dark-border mb-6">
<p class="text-xs text-slate-400 mb-1">Target URL</p>
<a href="{{ $campaign->target_url }}" target="_blank" class="text-sm text-purple-400 hover:text-purple-300 break-all">{{ $campaign->target_url }}</a>
</div>
@endif
<form action="{{ route('campaign.interact', $campaign) }}" method="POST">
@csrf
<button type="submit" class="btn-primary w-full justify-center py-3">Complete Task & Earn {{ number_format($campaign->points_per_action) }} Points</button>
</form>
</div>
</div>
@endsection
