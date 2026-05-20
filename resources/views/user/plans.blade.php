@extends('layouts.app')
@section('title','Upgrade Plan')
@section('content')
<div class="max-w-4xl mx-auto">
<div class="text-center mb-10">
<h1 class="text-3xl font-black text-white mb-3">Choose Your <span class="gradient-text">Earning Power</span></h1>
<p class="text-slate-400">Upgrade to multiply your daily earnings potential</p>
</div>
<div class="grid md:grid-cols-3 gap-6">
@foreach($plans as $plan)
@php $isCurrent = $current && $current->plan_id == $plan->id; @endphp
<div class="card p-6 flex flex-col {{ $plan->slug === 'pro' ? 'ring-2 ring-purple-500 relative' : '' }}">
@if($plan->slug === 'pro')
<div class="absolute -top-3 left-1/2 -translate-x-1/2">
<span class="gradient-bg text-white text-xs font-bold px-4 py-1 rounded-full">⭐ Most Popular</span>
</div>
@endif
<div class="mb-5">
<h3 class="text-xl font-bold text-white mb-1">{{ $plan->name }}</h3>
<p class="text-slate-400 text-sm">{{ $plan->description }}</p>
</div>
<div class="mb-6">
@if($plan->monthly_price == 0)
<p class="text-4xl font-black text-white">Free</p>
@else
<div>
<p class="text-4xl font-black text-white">${{ number_format($plan->monthly_price, 0) }}<span class="text-base font-normal text-slate-400">/mo</span></p>
<p class="text-xs text-slate-500 mt-1">${{ number_format($plan->yearly_price, 0) }}/year</p>
</div>
@endif
</div>
<ul class="space-y-3 mb-8 flex-1">
@foreach($plan->features ?? [] as $feature)
<li class="flex items-center gap-2 text-sm text-slate-300">
<svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
{{ $feature }}
</li>
@endforeach
</ul>
@if($isCurrent)
<div class="text-center py-3 rounded-2xl text-sm font-semibold text-green-400 bg-green-500/10 border border-green-500/20">✓ Current Plan</div>
@elseif($plan->monthly_price == 0)
<div class="text-center py-3 rounded-2xl text-sm font-medium text-slate-400 bg-dark-border">Free Plan</div>
@else
<form action="{{ route('subscribe.store', $plan) }}" method="POST">
@csrf
<button type="submit" class="{{ $plan->slug==='pro' ? 'btn-primary' : 'btn-secondary' }} w-full justify-center py-3">
Upgrade to {{ $plan->name }}
</button>
</form>
@endif
</div>
@endforeach
</div>
<p class="text-center text-sm text-slate-500 mt-6">All plans include full platform access. Cancel anytime.</p>
</div>
@endsection
