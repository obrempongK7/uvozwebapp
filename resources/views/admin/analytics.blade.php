@extends('layouts.admin')
@section('page_title','Analytics')
@section('content')
<div class="grid sm:grid-cols-2 gap-4 mb-6">
<div class="stat-card"><p class="text-xs text-slate-400 mb-1">DAU (last 24h)</p><p class="text-3xl font-black text-white">{{ number_format($retentionData['dau']) }}</p></div>
<div class="stat-card"><p class="text-xs text-slate-400 mb-1">MAU (last 30d)</p><p class="text-3xl font-black gradient-text">{{ number_format($retentionData['mau']) }}</p></div>
</div>
<div class="grid lg:grid-cols-2 gap-6 mb-6">
<div class="card p-5"><h3 class="font-semibold text-white mb-4">User Signups (30 days)</h3><canvas id="userChart" height="220"></canvas></div>
<div class="card p-5"><h3 class="font-semibold text-white mb-4">Revenue (30 days)</h3><canvas id="revenueChart" height="220"></canvas></div>
</div>
<div class="card p-5 mb-6"><h3 class="font-semibold text-white mb-4">Points Distributed (30 days)</h3><canvas id="pointsChart" height="120"></canvas></div>
<div class="card p-5">
<h3 class="font-semibold text-white mb-4">Top Content</h3>
<div class="space-y-3">
@foreach($topContent as $post)
<div class="flex items-center gap-3 py-2 border-b border-dark-border last:border-0">
<span class="text-slate-600 font-bold text-sm w-6 flex-shrink-0">{{ $loop->iteration }}</span>
<img src="{{ $post->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
<div class="flex-1 min-w-0"><p class="text-sm text-white truncate">{{ $post->title }}</p><p class="text-xs text-slate-400">{{ $post->user->display_name }}</p></div>
<div class="text-right"><p class="text-sm font-semibold text-purple-400">{{ number_format($post->plays_count) }} plays</p><p class="text-xs text-slate-500">Score: {{ number_format($post->engagement_score,1) }}</p></div>
</div>
@endforeach
</div>
</div>
@endsection
@section('scripts')
<script>
const co={responsive:true,plugins:{legend:{display:false}},scales:{x:{grid:{color:'#222a3d'},ticks:{color:'#64748B',font:{size:10}}},y:{grid:{color:'#222a3d'},ticks:{color:'#64748B',font:{size:10}}}}};
new Chart(document.getElementById('userChart'),{type:'line',data:{labels:{{ $dailyUsers->pluck('d')->toJson() }},datasets:[{data:{{ $dailyUsers->pluck('c')->toJson() }},borderColor:'#8B5CF6',backgroundColor:'rgba(139,92,246,.1)',fill:true,tension:.4,borderWidth:2,pointRadius:3,pointBackgroundColor:'#8B5CF6'}]},options:co});
new Chart(document.getElementById('revenueChart'),{type:'bar',data:{labels:{{ $revenueChart->pluck('d')->toJson() }},datasets:[{data:{{ $revenueChart->pluck('t')->toJson() }},backgroundColor:'rgba(16,185,129,.6)',borderColor:'#10B981',borderWidth:1,borderRadius:6}]},options:co});
new Chart(document.getElementById('pointsChart'),{type:'line',data:{labels:{{ $pointsChart->pluck('d')->toJson() }},datasets:[{data:{{ $pointsChart->pluck('t')->toJson() }},borderColor:'#06B6D4',backgroundColor:'rgba(6,182,212,.1)',fill:true,tension:.4,borderWidth:2,pointRadius:2}]},options:co});
</script>
@endsection
