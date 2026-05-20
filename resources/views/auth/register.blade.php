@extends('layouts.auth')
@section('title', 'Create Account')
@section('content')
<h2 class="text-2xl font-bold text-white mb-2">Create your account</h2>
<p class="text-slate-400 text-sm mb-6">Join millions earning from authentic engagement.</p>
<form method="POST" action="{{ route('register') }}" class="space-y-4">
@csrf
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Username</label>
<input type="text" name="username" value="{{ old('username') }}" placeholder="yourname" class="input-field" required>
@error('username')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
<input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" class="input-field" required>
@error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
<input type="password" name="password" placeholder="Min 8 characters" class="input-field" required>
@error('password')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Confirm Password</label>
<input type="password" name="password_confirmation" placeholder="Repeat password" class="input-field" required>
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Referral Code (optional)</label>
<input type="text" name="referral_code" value="{{ request('ref') }}" placeholder="XXXXXXXX" class="input-field">
</div>
<div class="flex items-start gap-3">
<input type="checkbox" name="terms" id="terms" value="1" class="mt-1 rounded" required>
<label for="terms" class="text-sm text-slate-400">I agree to the <a href="{{ route('terms') }}" class="text-purple-400 hover:text-purple-300">Terms of Service</a> and <a href="{{ route('privacy') }}" class="text-purple-400 hover:text-purple-300">Privacy Policy</a></label>
</div>
@error('terms')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
<button type="submit" class="btn-primary">Create Account</button>
</form>
<p class="text-center mt-6 text-sm text-slate-400">Already have an account? <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-medium">Sign in</a></p>
@endsection
