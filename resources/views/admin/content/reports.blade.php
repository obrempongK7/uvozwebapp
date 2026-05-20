@extends('layouts.admin')
@section('page_title','Content Reports')
@section('content')
<div class="flex gap-2 mb-4"><form class="flex gap-2"><select name="status" class="input-field py-2 text-sm"><option value="">All</option>@foreach(['pending','reviewed','actioned','dismissed'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select><button class="btn-primary py-2">Filter</button></form></div>
<div class="card overflow-hidden">
<table class="w-full"><thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">Reporter</th><th class="text-left px-5 py-3">Type</th><th class="text-left px-5 py-3">Reason</th><th class="text-left px-5 py-3">Date</th><th class="text-left px-5 py-3">Status</th><th class="text-left px-5 py-3">Actions</th></tr></thead>
<tbody>
@forelse($reports as $r)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><div class="flex items-center gap-2"><img src="{{ $r->reporter->avatar_url }}" class="w-7 h-7 rounded-full object-cover"><span class="text-sm text-white">{{ $r->reporter->display_name }}</span></div></td>
<td class="px-5 py-3 text-xs text-slate-400">{{ class_basename($r->reportable_type) }}</td>
<td class="px-5 py-3 text-sm text-slate-300">{{ $r->reason }}</td>
<td class="px-5 py-3 text-xs text-slate-500">{{ $r->created_at->format('M d') }}</td>
<td class="px-5 py-3">@if($r->status==='actioned')<span class="badge-active">Actioned</span>@elseif($r->status==='pending')<span class="badge-pending">Pending</span>@else<span class="text-xs text-slate-500">{{ ucfirst($r->status) }}</span>@endif</td>
<td class="px-5 py-3">
<form action="{{ route('admin.content.reports.action',$r) }}" method="POST" class="flex gap-2">@csrf
<select name="action" class="input-field py-1 text-xs w-28"><option value="reviewed">Reviewed</option><option value="actioned">Actioned</option><option value="dismissed">Dismissed</option></select>
<button type="submit" class="text-xs text-purple-400 hover:text-purple-300">Update</button>
</form>
</td>
</tr>
@empty<tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">No reports</td></tr>@endforelse
</tbody></table>
<div class="px-5 py-4 border-t border-dark-border">{{ $reports->links() }}</div>
</div>
@endsection
