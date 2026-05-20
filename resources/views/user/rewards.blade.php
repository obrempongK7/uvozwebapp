@extends('layouts.app')
@section('title','Reward Store')
@section('content')
<div class="max-w-4xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div><h1 class="text-2xl font-bold text-white">Reward Store</h1><p class="text-slate-400 text-sm mt-1">Redeem your points for amazing rewards</p></div>
<div class="card px-4 py-2 flex items-center gap-2" style="border-color:rgba(124,58,237,.3);">
<svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
<span class="font-bold text-white">{{ number_format($wallet?->points_balance ?? 0) }}</span>
<span class="text-xs text-slate-400">points</span>
</div>
</div>
@foreach($rewards as $category => $items)
<div class="mb-8">
<h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">{{ ucfirst(str_replace('_',' ',$category)) }}</h3>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
@foreach($items as $reward)
<div class="card p-4 flex flex-col card-hover">
@if($reward->image)<img src="{{ $reward->image_url }}" class="w-full h-28 object-cover rounded-xl mb-3">@endif
<h4 class="font-semibold text-white mb-1">{{ $reward->title }}</h4>
@if($reward->description)<p class="text-xs text-slate-400 mb-3 flex-1 line-clamp-2">{{ $reward->description }}</p>@endif
<div class="flex items-center justify-between mt-auto pt-2">
<div>
<p class="text-lg font-black text-amber-400">{{ number_format($reward->points_required) }} pts</p>
@if($reward->usd_value > 0)<p class="text-xs text-slate-500">≈ ${{ number_format($reward->usd_value, 2) }}</p>@endif
</div>
@if(($wallet?->points_balance ?? 0) >= $reward->points_required)
<form action="{{ route('wallet.rewards.redeem', $reward) }}" method="POST">
@csrf
<button type="submit" class="btn-primary text-xs py-2 px-4">Redeem</button>
</form>
@else
<button disabled class="btn-secondary text-xs py-2 px-4 opacity-50 cursor-not-allowed">Redeem</button>
@endif
</div>
</div>
@endforeach
</div>
</div>
@endforeach
@if($rewards->isEmpty())
<div class="card p-12 text-center"><p class="text-slate-400">No rewards available yet. Check back soon!</p></div>
@endif
@if($redemptions->count())
<div class="mt-8">
<h3 class="text-lg font-semibold text-white mb-4">My Redemptions</h3>
<div class="card overflow-hidden">
@foreach($redemptions as $r)
<div class="flex items-center gap-3 p-4 border-b border-dark-border last:border-0">
<div class="flex-1"><p class="text-sm font-medium text-white">{{ $r->reward->title }}</p><p class="text-xs text-slate-500">{{ $r->created_at->diffForHumans() }} · {{ number_format($r->points_spent) }} pts</p></div>
@if($r->status==='fulfilled')<span class="badge badge-green text-xs">Fulfilled</span>
@elseif($r->status==='pending')<span class="badge badge-amber text-xs">Pending</span>
@else<span class="text-xs text-slate-500">{{ ucfirst($r->status) }}</span>@endif
</div>
@endforeach
</div>
</div>
@endif
</div>
@endsection
