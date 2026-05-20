@extends('layouts.app')
@section('title','Referrals')
@section('content')
<div class="max-w-2xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-6">Referral Program</h1>
<!-- Referral Link Card -->
<div class="card p-6 mb-6" style="background:linear-gradient(135deg,rgba(124,58,237,.15),rgba(236,72,153,.1));border-color:rgba(124,58,237,.3);">
<div class="flex items-center gap-3 mb-3">
<div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/></svg></div>
<div><p class="font-bold text-white">Earn 10% of every referral's income</p><p class="text-xs text-slate-400">Lifetime commission on all earnings</p></div>
</div>
<div class="flex gap-2 mt-4">
<input type="text" value="{{ $referralLink }}" class="input-field text-sm flex-1" readonly id="refLink">
<button onclick="navigator.clipboard.writeText(document.getElementById('refLink').value);this.textContent='Copied!'" class="btn-primary py-2 px-4 text-sm">Copy Link</button>
</div>
<p class="text-xs text-slate-500 mt-2">Your referral code: <strong class="text-purple-400">{{ $user->referral_code }}</strong></p>
</div>
<!-- Stats -->
<div class="grid sm:grid-cols-2 gap-4 mb-6">
<div class="card p-5"><p class="text-xs text-slate-400 mb-1">Total Referrals</p><p class="text-3xl font-black text-white">{{ $referrals->total() }}</p></div>
<div class="card p-5"><p class="text-xs text-slate-400 mb-1">Total Earned</p><p class="text-3xl font-black gradient-text">{{ number_format($totalEarned) }} pts</p></div>
</div>
<!-- Referrals List -->
<div class="card overflow-hidden">
<div class="p-5 border-b border-dark-border"><h3 class="font-semibold text-white">Your Referrals</h3></div>
@forelse($referrals as $ref)
<div class="flex items-center gap-3 p-4 border-b border-dark-border last:border-0">
<img src="{{ $ref->referred->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
<div class="flex-1"><p class="text-sm font-medium text-white">{{ $ref->referred->display_name }}</p><p class="text-xs text-slate-400">Joined {{ $ref->created_at->diffForHumans() }}</p></div>
<div class="text-right"><p class="text-sm font-semibold text-amber-400">+{{ number_format($ref->total_points_earned) }} pts</p><p class="text-xs text-slate-500">earned for you</p></div>
</div>
@empty
<div class="p-8 text-center"><p class="text-slate-400">No referrals yet. Share your link to start earning!</p></div>
@endforelse
</div>
{{ $referrals->links() }}
</div>
@endsection
