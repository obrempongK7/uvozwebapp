@extends('layouts.admin')
@section('page_title','Fraud Flags')
@section('content')
<div class="card overflow-hidden">
<table class="w-full"><thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">User</th><th class="text-left px-5 py-3">Reason</th><th class="text-left px-5 py-3">Severity</th><th class="text-left px-5 py-3">IP</th><th class="text-left px-5 py-3">Date</th><th class="text-left px-5 py-3">Actions</th></tr></thead>
<tbody>
@forelse($flags as $f)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><div class="flex items-center gap-2"><img src="{{ $f->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover"><a href="{{ route('admin.users.show',$f->user) }}" class="text-sm text-white hover:text-purple-400">{{ $f->user->display_name }}</a></div></td>
<td class="px-5 py-3 text-xs text-slate-300 max-w-48 truncate">{{ $f->reason }}</td>
<td class="px-5 py-3">@if($f->severity==='high')<span class="badge-rejected">High</span>@elseif($f->severity==='medium')<span class="badge-pending">Medium</span>@else<span class="text-xs text-slate-500">Low</span>@endif</td>
<td class="px-5 py-3 text-xs text-slate-500 font-mono">{{ $f->ip_address }}</td>
<td class="px-5 py-3 text-xs text-slate-500">{{ $f->created_at->format('M d') }}</td>
<td class="px-5 py-3">
<form action="{{ route('admin.settings.fraud.action',$f) }}" method="POST" class="flex gap-2">@csrf
<select name="action" class="input-field py-1 text-xs w-28"><option value="reviewed">Reviewed</option><option value="dismissed">Dismiss</option><option value="actioned">Action</option></select>
<button type="submit" class="text-xs text-purple-400 hover:text-purple-300">Update</button>
</form>
</td>
</tr>
@empty<tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">No fraud flags 🎉</td></tr>@endforelse
</tbody></table>
<div class="px-5 py-4 border-t border-dark-border">{{ $flags->links() }}</div>
</div>
@endsection
