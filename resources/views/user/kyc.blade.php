@extends('layouts.app')
@section('title','KYC Verification')
@section('content')
<div class="max-w-xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-2">Identity Verification</h1>
<p class="text-slate-400 text-sm mb-6">Required to unlock higher withdrawal limits</p>
@if($kyc)
<div class="card p-6 mb-6">
<div class="flex items-center gap-3">
<div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $kyc->status==='approved'?'bg-green-500/20':($kyc->status==='rejected'?'bg-red-500/20':'bg-amber-500/20') }}">
@if($kyc->status==='approved')<svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
@elseif($kyc->status==='rejected')<svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
@else<svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>@endif
</div>
<div><p class="font-semibold text-white">Verification {{ ucfirst($kyc->status) }}</p><p class="text-sm text-slate-400">Submitted {{ $kyc->created_at->diffForHumans() }}</p>@if($kyc->rejection_reason)<p class="text-xs text-red-400 mt-1">Reason: {{ $kyc->rejection_reason }}</p>@endif</div>
</div>
</div>
@if($kyc->status !== 'approved' && $kyc->status !== 'pending')
@endif
@endif
@if(!$kyc || $kyc->status === 'rejected')
<div class="card p-6">
<form action="{{ route('kyc.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
@csrf
<div><label class="block text-sm font-medium text-slate-300 mb-2">Document Type</label>
<select name="document_type" class="input-field"><option value="national_id">National ID</option><option value="passport">Passport</option><option value="drivers_license">Driver's License</option></select></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Document Number</label><input type="text" name="document_number" class="input-field" placeholder="ID number..."></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Document Expiry</label><input type="date" name="document_expiry" class="input-field"></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Front of Document *</label><input type="file" name="document_front" accept="image/*" class="input-field" required></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Back of Document</label><input type="file" name="document_back" accept="image/*" class="input-field"></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Selfie with Document *</label><input type="file" name="selfie_image" accept="image/*" class="input-field" required></div>
<button type="submit" class="btn-primary">Submit for Verification</button>
</form>
</div>
@endif
</div>
@endsection
