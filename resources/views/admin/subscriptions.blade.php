@extends('layouts.admin')
@section('page_title','Subscription Plans')
@section('content')
<div class="grid md:grid-cols-3 gap-6">
@foreach($plans as $plan)
<div class="card p-6">
<h3 class="text-lg font-bold text-white mb-4">{{ $plan->name }}</h3>
<form action="{{ route('admin.subscriptions.update',$plan) }}" method="POST" class="space-y-3">
@csrf @method('PUT')
<div><label class="block text-xs text-slate-400 mb-1">Monthly Price ($)</label><input type="number" name="monthly_price" step="0.01" value="{{ $plan->monthly_price }}" class="input-field py-2 text-sm"></div>
<div><label class="block text-xs text-slate-400 mb-1">Yearly Price ($)</label><input type="number" name="yearly_price" step="0.01" value="{{ $plan->yearly_price }}" class="input-field py-2 text-sm"></div>
<div><label class="block text-xs text-slate-400 mb-1">Daily Points Cap</label><input type="number" name="daily_points_cap" value="{{ $plan->daily_points_cap }}" class="input-field py-2 text-sm"></div>
<div><label class="block text-xs text-slate-400 mb-1">Earning Boost (%)</label><input type="number" name="earning_boost_percent" value="{{ $plan->earning_boost_percent }}" class="input-field py-2 text-sm"></div>
<div class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }} class="rounded"><label class="text-xs text-slate-300">Active</label></div>
<button type="submit" class="btn-primary w-full justify-center text-sm py-2">Save Plan</button>
</form>
</div>
@endforeach
</div>
@endsection
