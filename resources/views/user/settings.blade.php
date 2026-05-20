@extends('layouts.app')
@section('title','Settings')
@section('content')
<div class="max-w-2xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-6">Settings</h1>
<div x-data="{ tab: 'account' }" class="space-y-6">
<!-- Tabs -->
<div class="flex gap-2 overflow-x-auto">
@foreach([['account','Account'],['privacy','Privacy'],['password','Password'],['notifications','Notifications'],['shipping','Shipping'],['2fa','Security']] as $t)
<button @click="tab='{{ $t[0] }}'" class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all whitespace-nowrap" :class="tab==='{{ $t[0] }}'?'gradient-bg text-white':'btn-secondary'">{{ $t[1] }}</button>
@endforeach
</div>
<!-- Account Tab -->
<div x-show="tab==='account'" class="card p-6 space-y-5">
<h2 class="font-semibold text-white">Account Settings</h2>
<form action="{{ route('settings.account') }}" method="POST" class="space-y-4">
@csrf @method('POST')
<div class="flex items-center gap-4">
<img src="{{ $user->avatar_url }}" class="w-16 h-16 rounded-2xl object-cover">
<div><p class="text-sm font-medium text-white mb-1">Profile Picture</p><input type="file" id="avatarInput" class="hidden" accept="image/*" onchange="uploadFile(this,'avatar')"><button type="button" onclick="document.getElementById('avatarInput').click()" class="btn-secondary text-xs py-2 px-3">Change Photo</button></div>
</div>
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-xs text-slate-400 mb-1.5">Display Name</label><input type="text" name="display_name" value="{{ $user->display_name }}" class="input-field"></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Username</label><input type="text" value="{{ $user->username }}" class="input-field bg-dark-border opacity-60" disabled></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Email</label><input type="email" name="email" value="{{ $user->email }}" class="input-field"></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Country</label><input type="text" name="country" value="{{ $user->country }}" placeholder="US" maxlength="2" class="input-field uppercase"></div>
</div>
<div><label class="block text-xs text-slate-400 mb-1.5">Bio</label><textarea name="bio" rows="3" class="input-field resize-none" maxlength="300">{{ $user->bio }}</textarea></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Website</label><input type="url" name="website" value="{{ $user->website }}" class="input-field"></div>
<button type="submit" class="btn-primary">Save Changes</button>
</form>
</div>
<!-- Privacy Tab -->
<div x-show="tab==='privacy'" class="card p-6 space-y-4">
<h2 class="font-semibold text-white mb-2">Privacy Settings</h2>
<form action="{{ route('settings.privacy') }}" method="POST" class="space-y-4">
@csrf
@foreach([['profile_public','Public Profile','Allow anyone to view your profile'],['allow_messages','Allow Messages','Let other users send you direct messages'],['show_earnings','Show Earnings','Display your earnings on your public profile']] as $pref)
<div class="flex items-center justify-between py-3 border-b border-dark-border last:border-0">
<div><p class="text-sm font-medium text-white">{{ $pref[1] }}</p><p class="text-xs text-slate-400">{{ $pref[2] }}</p></div>
<label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" name="{{ $pref[0] }}" value="1" {{ $user->profile?->{$pref[0]} ? 'checked' : '' }} class="sr-only peer"><div class="w-11 h-6 bg-dark-border peer-checked:bg-purple-600 rounded-full peer transition-colors"></div><div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div></label>
</div>
@endforeach
<button type="submit" class="btn-primary">Save Privacy</button>
</form>
</div>
<!-- Password Tab -->
<div x-show="tab==='password'" class="card p-6">
<h2 class="font-semibold text-white mb-4">Change Password</h2>
<form action="{{ route('settings.password') }}" method="POST" class="space-y-4">
@csrf
<div><label class="block text-xs text-slate-400 mb-1.5">Current Password</label><input type="password" name="current_password" class="input-field" required></div>
<div><label class="block text-xs text-slate-400 mb-1.5">New Password</label><input type="password" name="password" class="input-field" required minlength="8"></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Confirm New Password</label><input type="password" name="password_confirmation" class="input-field" required></div>
<button type="submit" class="btn-primary">Update Password</button>
</form>
</div>
<!-- Security / 2FA -->
<div x-show="tab==='2fa'" class="card p-6">
<h2 class="font-semibold text-white mb-4">Two-Factor Authentication</h2>
<div class="flex items-center justify-between p-4 rounded-2xl bg-dark-border">
<div><p class="font-medium text-white">2FA is {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}</p><p class="text-xs text-slate-400 mt-1">Add an extra layer of security to your account</p></div>
@if($user->two_factor_enabled)
<form action="{{ route('settings.2fa.disable') }}" method="POST">@csrf<button class="btn-secondary text-sm py-2 px-4">Disable</button></form>
@else
<form action="{{ route('settings.2fa.enable') }}" method="POST">@csrf<button class="btn-primary text-sm py-2 px-4">Enable</button></form>
@endif
</div>
<div class="mt-4 p-4 rounded-2xl bg-dark-border">
<h3 class="text-sm font-semibold text-white mb-2">Active Sessions</h3>
<a href="{{ route('settings.sessions') }}" class="text-sm text-purple-400 hover:text-purple-300">Manage sessions →</a>
</div>
<div class="mt-4">
<form action="{{ route('settings.delete') }}" method="POST" x-data x-on:submit.prevent="const p=prompt('Type your password to confirm account deletion:');if(p){$el.querySelector('[name=password]').value=p;$el.submit()}">
@csrf @method('DELETE')
<input type="hidden" name="password" value="">
<button type="submit" class="text-sm text-red-400 hover:text-red-300">Delete My Account</button>
</form>
</div>
</div>
<!-- Notifications -->
<div x-show="tab==='notifications'" class="card p-6">
<h2 class="font-semibold text-white mb-4">Notification Preferences</h2>
<form action="{{ route('settings.notifications') }}" method="POST" class="space-y-3">
@csrf
@foreach([['new_followers','New Followers','When someone follows you'],['new_comments','Comments','When someone comments on your post'],['earnings','Earnings','When you earn points'],['withdrawals','Withdrawals','Withdrawal status updates'],['messages','Messages','New direct messages']] as $n)
<div class="flex items-center justify-between py-3 border-b border-dark-border last:border-0">
<div><p class="text-sm text-white">{{ $n[1] }}</p><p class="text-xs text-slate-400">{{ $n[2] }}</p></div>
<label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" name="notif_{{ $n[0] }}" value="1" checked class="sr-only peer"><div class="w-11 h-6 bg-dark-border peer-checked:bg-purple-600 rounded-full peer transition-colors"></div><div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div></label>
</div>
@endforeach
<button type="submit" class="btn-primary">Save Preferences</button>
</form>
</div>
<!-- Shipping -->
<div x-show="tab==='shipping'" class="card p-6">
<h2 class="font-semibold text-white mb-4">Shipping Address (for physical rewards)</h2>
<form action="{{ route('settings.shipping') }}" method="POST" class="space-y-4">
@csrf
@php $addr = \App\Models\RewardShippingAddress::where('user_id',auth()->id())->where('is_default',true)->first(); @endphp
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-xs text-slate-400 mb-1.5">Full Name</label><input type="text" name="full_name" value="{{ $addr?->full_name }}" class="input-field" required></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Phone</label><input type="text" name="phone" value="{{ $addr?->phone }}" class="input-field" required></div>
</div>
<div><label class="block text-xs text-slate-400 mb-1.5">Address Line 1</label><input type="text" name="address_line1" value="{{ $addr?->address_line1 }}" class="input-field" required></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Address Line 2</label><input type="text" name="address_line2" value="{{ $addr?->address_line2 }}" class="input-field"></div>
<div class="grid sm:grid-cols-3 gap-4">
<div><label class="block text-xs text-slate-400 mb-1.5">City</label><input type="text" name="city" value="{{ $addr?->city }}" class="input-field" required></div>
<div><label class="block text-xs text-slate-400 mb-1.5">State</label><input type="text" name="state" value="{{ $addr?->state }}" class="input-field"></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Postal Code</label><input type="text" name="postal_code" value="{{ $addr?->postal_code }}" class="input-field"></div>
</div>
<div><label class="block text-xs text-slate-400 mb-1.5">Country</label><input type="text" name="country" value="{{ $addr?->country }}" class="input-field" required></div>
<button type="submit" class="btn-primary">Save Address</button>
</form>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>
function uploadFile(input, type) {
    const fd = new FormData();
    fd.append(type, input.files[0]);
    fd.append('_token', '{{ csrf_token() }}');
    fetch('{{ route('profile.avatar') }}', {method:'POST', body:fd})
    .then(r=>r.json()).then(d=>{ if(d.url) document.querySelector('img[src*="avatar"]').src=d.url; });
}
</script>
@endsection
