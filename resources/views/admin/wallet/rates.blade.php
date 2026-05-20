@extends('layouts.admin')
@section('page_title','Reward Rates')
@section('content')
<div class="card overflow-hidden">
<form action="{{ route('admin.wallet.rates.update') }}" method="POST">
@csrf
<table class="w-full">
<thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">Action Type</th><th class="text-left px-5 py-3">Points Value</th><th class="text-left px-5 py-3">Daily Limit</th><th class="text-left px-5 py-3">Active</th></tr></thead>
<tbody>
@foreach($rates as $rate)
<tr class="border-b border-dark-border">
<td class="px-5 py-3 text-sm text-white">{{ ucfirst(str_replace('_',' ',$rate->action_type)) }}@if($rate->country)<span class="ml-2 text-xs text-slate-500">({{ $rate->country }})</span>@endif</td>
<td class="px-5 py-3"><input type="number" name="rates[{{ $rate->id }}][points_value]" value="{{ $rate->points_value }}" class="input-field py-1.5 w-24 text-sm"></td>
<td class="px-5 py-3"><input type="number" name="rates[{{ $rate->id }}][daily_limit]" value="{{ $rate->daily_limit }}" placeholder="Unlimited" class="input-field py-1.5 w-28 text-sm"></td>
<td class="px-5 py-3"><label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" name="rates[{{ $rate->id }}][is_active]" value="1" {{ $rate->is_active ? 'checked' : '' }} class="sr-only peer"><div class="w-10 h-5 bg-dark-border peer-checked:bg-purple-600 rounded-full peer transition-colors"></div><div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div></label></td>
</tr>
@endforeach
</tbody>
</table>
<div class="px-5 py-4 border-t border-dark-border"><button type="submit" class="btn-primary">Save All Rates</button></div>
</form>
</div>
@endsection
