@extends('layouts.admin')
@section('page_title','KYC Review')
@section('content')
<div class="max-w-2xl mx-auto">
<div class="card p-6 mb-4">
<div class="flex items-center gap-3 mb-4">
<img src="{{ $kyc->user->avatar_url }}" class="w-12 h-12 rounded-full object-cover">
<div><h2 class="font-bold text-white">{{ $kyc->user->display_name }}</h2><p class="text-sm text-slate-400">{{ $kyc->user->email }}</p></div>
<div class="ml-auto">@if($kyc->status==='approved')<span class="badge-active">Approved</span>@elseif($kyc->status==='rejected')<span class="badge-rejected">Rejected</span>@else<span class="badge-pending">Pending</span>@endif</div>
</div>
<div class="grid sm:grid-cols-2 gap-4 mb-4">
<div><p class="text-xs text-slate-400">Document Type</p><p class="text-sm font-medium text-white">{{ ucfirst(str_replace('_',' ',$kyc->document_type)) }}</p></div>
<div><p class="text-xs text-slate-400">Document Number</p><p class="text-sm font-medium text-white">{{ $kyc->document_number ?? 'Not provided' }}</p></div>
<div><p class="text-xs text-slate-400">Expiry Date</p><p class="text-sm font-medium text-white">{{ $kyc->document_expiry?->format('M d, Y') ?? 'Not provided' }}</p></div>
</div>
<div class="grid sm:grid-cols-3 gap-3">
@if($kyc->document_front)<div><p class="text-xs text-slate-400 mb-1">Front</p><a href="{{ asset('storage/'.$kyc->document_front) }}" target="_blank"><img src="{{ asset('storage/'.$kyc->document_front) }}" class="w-full h-32 object-cover rounded-xl"></a></div>@endif
@if($kyc->document_back)<div><p class="text-xs text-slate-400 mb-1">Back</p><a href="{{ asset('storage/'.$kyc->document_back) }}" target="_blank"><img src="{{ asset('storage/'.$kyc->document_back) }}" class="w-full h-32 object-cover rounded-xl"></a></div>@endif
@if($kyc->selfie_image)<div><p class="text-xs text-slate-400 mb-1">Selfie</p><a href="{{ asset('storage/'.$kyc->selfie_image) }}" target="_blank"><img src="{{ asset('storage/'.$kyc->selfie_image) }}" class="w-full h-32 object-cover rounded-xl"></a></div>@endif
</div>
</div>
@if($kyc->status === 'pending')
<div class="flex gap-3">
<form action="{{ route('admin.kyc.approve',$kyc) }}" method="POST" class="flex-1">@csrf<button type="submit" class="btn-primary w-full justify-center">✓ Approve & Verify User</button></form>
<form action="{{ route('admin.kyc.reject',$kyc) }}" method="POST" class="flex-1" x-data x-on:submit.prevent="const r=prompt('Rejection reason:');if(r){$el.querySelector('[name=reason]').value=r;$el.submit()}">@csrf<input type="hidden" name="reason" value=""><button type="submit" class="w-full py-2.5 rounded-xl bg-red-500/15 text-red-400 font-semibold hover:bg-red-500/25">✗ Reject</button></form>
</div>
@endif
</div>
@endsection
