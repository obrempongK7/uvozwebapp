@extends('layouts.admin')
@section('page_title','Settings')
@section('content')
<div x-data="{ tab: 'general' }" class="space-y-6">
<div class="flex gap-2 overflow-x-auto">
@foreach([['general','General'],['payment','Payment'],['rewards','Rewards'],['seo','SEO']] as $t)
<button @click="tab='{{ $t[0] }}'" class="px-4 py-2 rounded-xl text-sm font-medium transition-all whitespace-nowrap" :class="tab==='{{ $t[0] }}'?'gradient-bg text-white':'btn-secondary'">{{ $t[1] }}</button>
@endforeach
</div>
<div x-show="tab==='general'" class="card p-6">
<h2 class="font-semibold text-white mb-4">General Settings</h2>
<form action="{{ route('admin.settings.general') }}" method="POST" class="space-y-4">
@csrf
@foreach([['site_name','Site Name','text'],['site_tagline','Tagline','text'],['site_description','Description','textarea'],['site_email','Contact Email','email'],['support_email','Support Email','email'],['site_url','Site URL','url']] as $s)
<div><label class="block text-xs text-slate-400 mb-1.5">{{ $s[1] }}</label>
@if($s[2]==='textarea')<textarea name="{{ $s[0] }}" class="input-field resize-none" rows="2">{{ \App\Models\SiteSetting::get($s[0]) }}</textarea>
@else<input type="{{ $s[2] }}" name="{{ $s[0] }}" value="{{ \App\Models\SiteSetting::get($s[0]) }}" class="input-field">@endif</div>
@endforeach
<div class="flex items-center justify-between py-2"><span class="text-sm text-slate-300">Maintenance Mode</span><label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" name="maintenance_mode" value="1" {{ \App\Models\SiteSetting::get('maintenance_mode') ? 'checked' : '' }} class="sr-only peer"><div class="w-11 h-6 bg-dark-border peer-checked:bg-purple-600 rounded-full peer transition-colors"></div><div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div></label></div>
<div class="flex items-center justify-between py-2"><span class="text-sm text-slate-300">Open Registration</span><label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" name="registration_open" value="1" {{ \App\Models\SiteSetting::get('registration_open',true) ? 'checked' : '' }} class="sr-only peer"><div class="w-11 h-6 bg-dark-border peer-checked:bg-purple-600 rounded-full peer transition-colors"></div><div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div></label></div>
<button type="submit" class="btn-primary">Save Settings</button>
</form>
</div>
<div x-show="tab==='payment'" class="card p-6">
<h2 class="font-semibold text-white mb-4">Payment Gateway Settings</h2>
<form action="{{ route('admin.settings.payment') }}" method="POST" class="space-y-4">
@csrf
<div class="p-4 rounded-2xl bg-dark-border">
<div class="flex items-center justify-between mb-3"><span class="font-medium text-white">Stripe</span><label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" name="stripe_enabled" value="1" {{ \App\Models\SiteSetting::get('stripe_enabled') ? 'checked' : '' }} class="sr-only peer"><div class="w-11 h-6 bg-dark-border peer-checked:bg-purple-600 rounded-full peer transition-colors"></div><div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div></label></div>
<input type="text" name="stripe_key" value="{{ config('services.stripe.key') }}" placeholder="Stripe Publishable Key" class="input-field mb-2">
<input type="password" name="stripe_secret" placeholder="Stripe Secret Key" class="input-field">
</div>
<div class="p-4 rounded-2xl bg-dark-border">
<div class="flex items-center justify-between mb-3"><span class="font-medium text-white">Paystack</span><label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" name="paystack_enabled" value="1" {{ \App\Models\SiteSetting::get('paystack_enabled') ? 'checked' : '' }} class="sr-only peer"><div class="w-11 h-6 bg-dark-border peer-checked:bg-purple-600 rounded-full peer transition-colors"></div><div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div></label></div>
<input type="text" name="paystack_public_key" placeholder="Paystack Public Key" class="input-field mb-2">
<input type="password" name="paystack_secret_key" placeholder="Paystack Secret Key" class="input-field">
</div>
<button type="submit" class="btn-primary">Save Payment Settings</button>
</form>
</div>
<div x-show="tab==='rewards'" class="card p-6">
<h2 class="font-semibold text-white mb-4">Reward Economy Settings</h2>
<form action="{{ route('admin.settings.rewards') }}" method="POST" class="space-y-4">
@csrf
@foreach([['points_per_usd','Points Per $1 USD','1000'],['min_withdrawal','Minimum Withdrawal (USD)','5'],['referral_commission','Referral Commission (%)','10']] as $s)
<div><label class="block text-xs text-slate-400 mb-1.5">{{ $s[1] }}</label><input type="number" name="{{ $s[0] }}" value="{{ \App\Models\SiteSetting::get($s[0],$s[2]) }}" class="input-field"></div>
@endforeach
<button type="submit" class="btn-primary">Save Reward Settings</button>
</form>
</div>
<div x-show="tab==='seo'" class="card p-6">
<h2 class="font-semibold text-white mb-4">SEO Settings</h2>
<form action="{{ route('admin.settings.seo') }}" method="POST" class="space-y-4">
@csrf
<div><label class="block text-xs text-slate-400 mb-1.5">Meta Title</label><input type="text" name="meta_title" value="{{ \App\Models\SiteSetting::get('meta_title') }}" class="input-field"></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Meta Description</label><textarea name="meta_description" rows="3" class="input-field resize-none">{{ \App\Models\SiteSetting::get('meta_description') }}</textarea></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Google Analytics ID</label><input type="text" name="google_analytics_id" value="{{ \App\Models\SiteSetting::get('google_analytics_id') }}" placeholder="G-XXXXXXXXXX" class="input-field"></div>
<button type="submit" class="btn-primary">Save SEO Settings</button>
</form>
</div>
</div>
@endsection
