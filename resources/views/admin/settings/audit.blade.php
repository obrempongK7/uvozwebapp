@extends('layouts.admin')
@section('page_title','Audit Logs')
@section('content')
<div class="card overflow-hidden">
<table class="w-full"><thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">User</th><th class="text-left px-5 py-3">Action</th><th class="text-left px-5 py-3">Target</th><th class="text-left px-5 py-3">IP</th><th class="text-left px-5 py-3">Date</th></tr></thead>
<tbody>
@forelse($logs as $log)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><span class="text-sm text-white">{{ $log->user?->display_name ?? 'System' }}</span></td>
<td class="px-5 py-3"><span class="badge badge-purple text-xs">{{ $log->action }}</span></td>
<td class="px-5 py-3 text-xs text-slate-400">{{ $log->model_type ? class_basename($log->model_type).'#'.$log->model_id : '-' }}</td>
<td class="px-5 py-3 text-xs text-slate-500 font-mono">{{ $log->ip_address }}</td>
<td class="px-5 py-3 text-xs text-slate-500">{{ $log->created_at->format('M d H:i') }}</td>
</tr>
@empty<tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">No audit logs</td></tr>@endforelse
</tbody></table>
<div class="px-5 py-4 border-t border-dark-border">{{ $logs->links() }}</div>
</div>
@endsection
