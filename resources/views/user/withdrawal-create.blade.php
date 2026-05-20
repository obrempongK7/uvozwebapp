@extends('layouts.app')
@section('title','Withdraw Funds')
@section('content')
<div class="max-w-xl mx-auto">
<div class="mb-6"><h1 class="text-2xl font-bold text-white">Withdraw Funds</h1><p class="text-slate-400 text-sm mt-1">Minimum withdrawal: $5.00</p></div>
<div class="card p-4 mb-6 flex items-center gap-3" style="border-color:rgba(16,185,129,.3);background:rgba(16,185,129,.05);">
<svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
<div><p class="text-sm font-semibold text-green-400">Available Balance</p><p class="text-xl font-black text-white">${{ number_format($wallet?->usd_balance ?? 0, 2) }}</p></div>
</div>
<div class="card p-6">
<form action="{{ route('withdrawal.store') }}" method="POST" x-data="{ method: 'mobile_money' }" class="space-y-5">
@csrf
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Amount (USD)</label>
<input type="number" name="amount" step="0.01" min="5" max="{{ $wallet?->usd_balance ?? 0 }}" placeholder="5.00" class="input-field" required>
@error('amount')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-3">Withdrawal Method</label>
<div class="grid grid-cols-2 gap-3">
@foreach([['mobile_money','📱 Mobile Money'],['bank_transfer','🏦 Bank Transfer'],['paypal','💳 PayPal'],['usdt','₿ USDT']] as $m)
<label class="cursor-pointer">
<input type="radio" name="method" value="{{ $m[0] }}" x-model="method" class="sr-only">
<div class="p-3 rounded-2xl border text-sm font-medium text-center transition-all" :class="method==='{{ $m[0] }}'?'border-purple-500 bg-purple-500/15 text-purple-300':'border-dark-border text-slate-400 hover:border-slate-500'">{{ $m[1] }}</div>
</label>
@endforeach
</div>
</div>
<!-- Mobile Money Fields -->
<div x-show="method==='mobile_money'" class="space-y-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">Phone Number</label><input type="text" name="payment_details[phone]" placeholder="+1234567890" class="input-field"></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Network</label><select name="payment_details[network]" class="input-field"><option value="mpesa">M-Pesa</option><option value="airtel">Airtel Money</option><option value="mtn">MTN MoMo</option><option value="orange">Orange Money</option><option value="other">Other</option></select></div>
</div>
<!-- Bank Fields -->
<div x-show="method==='bank_transfer'" class="space-y-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">Account Name</label><input type="text" name="payment_details[account_name]" class="input-field"></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Account Number</label><input type="text" name="payment_details[account_number]" class="input-field"></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Bank Name</label><input type="text" name="payment_details[bank_name]" class="input-field"></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Routing/SWIFT</label><input type="text" name="payment_details[routing]" class="input-field"></div>
</div>
<!-- PayPal Fields -->
<div x-show="method==='paypal'" class="space-y-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">PayPal Email</label><input type="email" name="payment_details[paypal_email]" class="input-field"></div>
</div>
<!-- USDT Fields -->
<div x-show="method==='usdt'" class="space-y-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">USDT Wallet Address (TRC20)</label><input type="text" name="payment_details[wallet_address]" class="input-field font-mono text-sm"></div>
</div>
<div class="pt-2 flex gap-4">
<button type="submit" class="btn-primary">Submit Withdrawal Request</button>
<a href="{{ route('wallet.index') }}" class="btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
