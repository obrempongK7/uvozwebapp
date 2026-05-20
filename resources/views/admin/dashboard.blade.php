@extends('layouts.admin')
@section('page_title','Dashboard')
@section('content')
<div class="space-y-6">
<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
@foreach([
['Total Users', number_format($stats['total_users']), '+'.number_format($stats['new_users_today']).' today', '#7C3AED', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
['Active Users', number_format($stats['active_users']), '30-day actives', '#10B981', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
['Pending Withdrawals', number_format($stats['pending_withdrawals']), 'Needs review', '#F59E0B', 'M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z'],
['Total Paid Out', '$'.number_format($stats['total_usd_paid'],2), 'Completed withdrawals', '#EC4899', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
] as $s)
<div class="stat-card">
<div class="flex items-center justify-between mb-3">
<p class="text-xs text-slate-400 font-medium">{{ $s[0] }}</p>
<div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:{{ $s[3] }}20">
<svg class="w-4 h-4" style="color:{{ $s[3] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s[4] }}"/></svg>
</div>
</div>
<p class="text-2xl font-black text-white">{{ $s[1] }}</p>
<p class="text-xs mt-1" style="color:{{ $s[3] }}">{{ $s[2] }}</p>
</div>
@endforeach
</div>

<!-- Action Alerts -->
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
@foreach([
['Pending KYC', $stats['pending_kyc'], route('admin.kyc.index'), '#F59E0B'],
['Pending Campaigns', $stats['pending_campaigns'], route('admin.campaigns.index'), '#8B5CF6'],
['Open Reports', $stats['open_reports'], route('admin.content.reports'), '#EF4444'],
['Fraud Flags', $stats['fraud_flags'], route('admin.settings.fraud'), '#EC4899'],
] as $alert)
@if($alert[1] > 0)
<a href="{{ $alert[2] }}" class="stat-card flex items-center gap-3 hover:bg-dark-hover transition-colors">
<div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm" style="background:{{ $alert[3] }}30;color:{{ $alert[3] }}">{{ $alert[1] }}</div>
<div><p class="text-sm font-semibold text-white">{{ $alert[0] }}</p><p class="text-xs" style="color:{{ $alert[3] }}">Needs attention →</p></div>
</a>
@endif
@endforeach
</div>

<!-- Charts -->
<div class="grid lg:grid-cols-2 gap-6">
<div class="card p-5">
<h3 class="font-semibold text-white mb-4">User Growth (30 days)</h3>
<canvas id="userChart" height="200"></canvas>
</div>
<div class="card p-5">
<h3 class="font-semibold text-white mb-4">Points Distributed (30 days)</h3>
<canvas id="pointsChart" height="200"></canvas>
</div>
</div>

<!-- Recent Withdrawals -->
<div class="card p-5">
<div class="flex items-center justify-between mb-4">
<h3 class="font-semibold text-white">Pending Withdrawals</h3>
<a href="{{ route('admin.wallet.withdrawals') }}" class="text-xs text-purple-400 hover:text-purple-300">View all →</a>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead><tr class="text-xs text-slate-400 border-b border-dark-border"><th class="text-left pb-3">User</th><th class="text-left pb-3">Amount</th><th class="text-left pb-3">Method</th><th class="text-left pb-3">Date</th><th class="text-left pb-3">Action</th></tr></thead>
<tbody>
@forelse($recentWithdrawals as $w)
<tr class="border-b border-dark-border table-row">
<td class="py-3"><div class="flex items-center gap-2"><img src="{{ $w->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover"><span class="text-sm text-white">{{ $w->user->display_name }}</span></div></td>
<td class="py-3 text-sm font-semibold text-green-400">${{ number_format($w->amount,2) }}</td>
<td class="py-3 text-sm text-slate-400">{{ ucfirst(str_replace('_',' ',$w->method)) }}</td>
<td class="py-3 text-xs text-slate-500">{{ $w->created_at->diffForHumans() }}</td>
<td class="py-3">
<form action="{{ route('admin.wallet.withdrawals.approve',$w) }}" method="POST" class="inline">@csrf<button type="submit" class="text-xs text-green-400 hover:text-green-300 mr-3">Approve</button></form>
<a href="{{ route('admin.wallet.withdrawals') }}" class="text-xs text-slate-400 hover:text-white">Review</a>
</td>
</tr>
@empty
<tr><td colspan="5" class="py-6 text-center text-slate-400 text-sm">No pending withdrawals</td></tr>
@endforelse
</tbody>
</table>
</div>
</div>

<!-- Recent Users -->
<div class="card p-5">
<div class="flex items-center justify-between mb-4">
<h3 class="font-semibold text-white">Recent Users</h3>
<a href="{{ route('admin.users.index') }}" class="text-xs text-purple-400 hover:text-purple-300">View all →</a>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead><tr class="text-xs text-slate-400 border-b border-dark-border"><th class="text-left pb-3">User</th><th class="text-left pb-3">Email</th><th class="text-left pb-3">Country</th><th class="text-left pb-3">Joined</th><th class="text-left pb-3">Status</th></tr></thead>
<tbody>
@foreach($recentUsers as $u)
<tr class="border-b border-dark-border table-row">
<td class="py-3"><div class="flex items-center gap-2"><img src="{{ $u->avatar_url }}" class="w-7 h-7 rounded-full object-cover"><a href="{{ route('admin.users.show',$u) }}" class="text-sm text-white hover:text-purple-400">{{ $u->display_name }}</a></div></td>
<td class="py-3 text-xs text-slate-400">{{ $u->email }}</td>
<td class="py-3 text-xs text-slate-400">{{ $u->country ?? '-' }}</td>
<td class="py-3 text-xs text-slate-500">{{ $u->created_at->diffForHumans() }}</td>
<td class="py-3">@if($u->is_banned)<span class="badge-rejected">Banned</span>@elseif($u->is_verified)<span class="badge-active">Verified</span>@else<span class="badge-pending">Unverified</span>@endif</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>
const ug={{ $userGrowth->pluck('date') }};
const uv={{ $userGrowth->pluck('count') }};
const pg={{ $pointsChart->pluck('date') }};
const pv={{ $pointsChart->pluck('total') }};
const opts={responsive:true,plugins:{legend:{display:false}},scales:{x:{grid:{color:'#1E293B'},ticks:{color:'#64748B',font:{size:10}}},y:{grid:{color:'#1E293B'},ticks:{color:'#64748B',font:{size:10}}}}};
new Chart(document.getElementById('userChart'),{type:'line',data:{labels:ug,datasets:[{data:uv,borderColor:'#7C3AED',backgroundColor:'rgba(124,58,237,.1)',fill:true,tension:.4,borderWidth:2,pointRadius:3,pointBackgroundColor:'#7C3AED'}]},options:opts});
new Chart(document.getElementById('pointsChart'),{type:'bar',data:{labels:pg,datasets:[{data:pv,backgroundColor:'rgba(236,72,153,.6)',borderColor:'#EC4899',borderWidth:1,borderRadius:6}]},options:opts});
</script>
@endsection
