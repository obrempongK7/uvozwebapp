@extends('layouts.admin')
@section('page_title','KYC Verifications')
@section('content')
<div class="flex gap-2 mb-4">
<form class="flex gap-2">
<select name="status" class="input-field py-2 text-sm"><option value="">All</option>@foreach(['pending','approved','rejected'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select>
<button type="submit" class="btn-primary py-2">Filter</button>
</form>
</div>
<div class="card overflow-hidden">
<table class="w-full">
<thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">User</th><th class="text-left px-5 py-3">Document</th><th class="text-left px-5 py-3">Submitted</th><th class="text-left px-5 py-3">Status</th><th class="text-left px-5 py-3">Actions</th></tr></thead>
<tbody>
@forelse($kycs as $kyc)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><div class="flex items-center gap-2"><img src="{{ $kyc->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover"><span class="text-sm text-white">{{ $kyc->user->display_name }}</span></div></td>
<td class="px-5 py-3 text-sm text-slate-400">{{ ucfirst(str_replace('_',' ',$kyc->document_type)) }}</td>
<td class="px-5 py-3 text-xs text-slate-500">{{ $kyc->created_at->format('M d, Y') }}</td>
<td class="px-5 py-3">@if($kyc->status==='approved')<span class="badge-active">Approved</span>@elseif($kyc->status==='rejected')<span class="badge-rejected">Rejected</span>@else<span class="badge-pending">Pending</span>@endif</td>
<td class="px-5 py-3">
<div class="flex gap-2">
<a href="{{ route('admin.kyc.show',$kyc) }}" class="text-xs text-purple-400 hover:text-purple-300">Review</a>
@if($kyc->status==='pending')
<form action="{{ route('admin.kyc.approve',$kyc) }}" method="POST" class="inline">@csrf<button class="text-xs text-green-400 hover:text-green-300">Approve</button></form>
<form action="{{ route('admin.kyc.reject',$kyc) }}" method="POST" class="inline" x-data x-on:submit.prevent="const r=prompt('Rejection reason:');if(r){$el.querySelector('[name=reason]').value=r;$el.submit()}">@csrf<input type="hidden" name="reason" value=""><button class="text-xs text-red-400 hover:text-red-300">Reject</button></form>
@endif
</div>
</td>
</tr>
@empty<tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">No KYC submissions</td></tr>@endforelse
</tbody>
</table>
<div class="px-5 py-4 border-t border-dark-border">{{ $kycs->links() }}</div>
</div>
@endsection
