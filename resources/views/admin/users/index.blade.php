@extends('layouts.admin')
@section('page_title','Users')
@section('content')
<div class="flex items-center justify-between mb-6">
<p class="text-slate-400 text-sm">{{ $users->total() }} total users</p>
<form class="flex gap-2">
<input name="search" value="{{ request('search') }}" placeholder="Search users..." class="input-field py-2 text-sm w-56">
<select name="role" class="input-field py-2 text-sm w-32"><option value="">All roles</option><option value="user">User</option><option value="moderator">Moderator</option><option value="admin">Admin</option></select>
<select name="status" class="input-field py-2 text-sm w-32"><option value="">All status</option><option value="active">Active</option><option value="banned">Banned</option></select>
<button type="submit" class="btn-primary py-2">Filter</button>
</form>
</div>
<div class="card overflow-hidden">
<table class="w-full">
<thead class="border-b border-dark-border"><tr class="text-xs text-slate-400">
<th class="text-left px-5 py-3">User</th><th class="text-left px-5 py-3">Email</th><th class="text-left px-5 py-3">Points</th><th class="text-left px-5 py-3">Plan</th><th class="text-left px-5 py-3">Joined</th><th class="text-left px-5 py-3">Status</th><th class="text-left px-5 py-3">Actions</th>
</tr></thead>
<tbody>
@forelse($users as $u)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3">
<div class="flex items-center gap-3">
<img src="{{ $u->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
<div><a href="{{ route('admin.users.show',$u) }}" class="text-sm font-medium text-white hover:text-purple-400">{{ $u->display_name }}</a><p class="text-xs text-slate-500">@{{ $u->username }}</p></div>
@if($u->is_verified)<svg class="w-3 h-3 text-purple-400 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>@endif
</div>
</td>
<td class="px-5 py-3 text-xs text-slate-400">{{ $u->email }}</td>
<td class="px-5 py-3 text-sm font-semibold text-amber-400">{{ number_format($u->wallet?->points_balance ?? 0) }}</td>
<td class="px-5 py-3 text-xs text-slate-400">{{ $u->subscription?->plan->name ?? 'Free' }}</td>
<td class="px-5 py-3 text-xs text-slate-500">{{ $u->created_at->format('M d, Y') }}</td>
<td class="px-5 py-3">@if($u->is_banned)<span class="badge-rejected">Banned</span>@elseif($u->is_verified)<span class="badge-active">Verified</span>@else<span class="badge-pending">Active</span>@endif</td>
<td class="px-5 py-3">
<div class="flex items-center gap-2">
<a href="{{ route('admin.users.show',$u) }}" class="text-xs text-purple-400 hover:text-purple-300">View</a>
@if(!$u->is_verified)<form action="{{ route('admin.users.verify',$u) }}" method="POST" class="inline">@csrf<button class="text-xs text-green-400 hover:text-green-300">Verify</button></form>@endif
@if(!$u->is_banned)
<form action="{{ route('admin.users.ban',$u) }}" method="POST" class="inline" x-data x-on:submit.prevent="const r=prompt('Ban reason:');if(r){$el.querySelector('[name=reason]').value=r;$el.submit()}">@csrf<input type="hidden" name="reason" value=""><button class="text-xs text-red-400 hover:text-red-300" type="submit">Ban</button></form>
@else
<form action="{{ route('admin.users.unban',$u) }}" method="POST" class="inline">@csrf<button class="text-xs text-blue-400 hover:text-blue-300">Unban</button></form>
@endif
</div>
</td>
</tr>
@empty
<tr><td colspan="7" class="px-5 py-8 text-center text-slate-400">No users found</td></tr>
@endforelse
</tbody>
</table>
<div class="px-5 py-4 border-t border-dark-border">{{ $users->links() }}</div>
</div>
@endsection
