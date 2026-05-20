@extends('layouts.app')
@section('title','Campaigns')
@section('content')
<div class="flex items-center justify-between mb-6">
<div><h1 class="text-2xl font-bold text-white">Campaign Marketplace</h1><p class="text-slate-400 text-sm mt-1">Complete tasks and earn extra points</p></div>
<a href="{{ route('campaign.create') }}" class="btn-secondary text-sm py-2 px-4">Create Campaign</a>
</div>
<div class="grid md:grid-cols-2 gap-4">
@forelse($campaigns as $campaign)
<div class="card p-5 card-hover">
@if($campaign->cover_image)<img src="{{ asset('storage/'.$campaign->cover_image) }}" class="w-full h-32 object-cover rounded-2xl mb-4">@endif
<div class="flex items-start justify-between mb-3">
<div>
<h3 class="font-semibold text-white">{{ $campaign->title }}</h3>
<p class="text-xs text-slate-400 mt-1">by {{ $campaign->advertiser->display_name }}</p>
</div>
<div class="text-right flex-shrink-0 ml-3">
<p class="text-xl font-black text-amber-400">+{{ number_format($campaign->points_per_action) }}</p>
<p class="text-xs text-slate-500">points</p>
</div>
</div>
@if($campaign->description)<p class="text-sm text-slate-400 mb-4 line-clamp-2">{{ $campaign->description }}</p>@endif
<div class="flex items-center justify-between">
<div class="flex items-center gap-3">
<span class="badge badge-purple text-xs">{{ ucfirst(str_replace('_',' ',$campaign->type)) }}</span>
@if($campaign->target_countries)<span class="text-xs text-slate-500">{{ implode(', ',array_slice($campaign->target_countries,0,2)) }}{{ count($campaign->target_countries)>2?' +'.count($campaign->target_countries)-2:''}}</span>@endif
</div>
<form action="{{ route('campaign.interact',$campaign) }}" method="POST">
@csrf
<button type="submit" class="btn-primary py-2 px-4 text-sm">
{{ $campaign->type === 'link_click' ? 'Click & Earn' : 'Complete Task' }}
</button>
</form>
</div>
</div>
@empty
<div class="md:col-span-2 card p-12 text-center"><svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg><h3 class="text-lg font-semibold text-white mb-2">No active campaigns</h3><p class="text-slate-400 text-sm">Check back soon for new earning opportunities</p></div>
@endforelse
</div>
{{ $campaigns->links() }}
@endsection
