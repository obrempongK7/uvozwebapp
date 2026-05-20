@extends('layouts.auth')
@section('title', 'Sign In')
@section('content')
<h2 class="text-2xl font-bold text-white mb-2">Welcome back</h2>
<p class="text-slate-400 text-sm mb-6">Sign in to continue earning.</p>
<form method="POST" action="{{ route('login') }}" class="space-y-4">
@csrf
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Email or Username</label>
<input type="text" name="login" value="{{ old('login') }}" placeholder="email or username" class="input-field" required autofocus>
@error('login')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
</div>
<div>
<div class="flex justify-between items-center mb-2">
<label class="text-sm font-medium text-slate-300">Password</label>
<a href="{{ route('password.request') }}" class="text-xs text-purple-400 hover:text-purple-300">Forgot password?</a>
</div>
<input type="password" name="password" placeholder="Your password" class="input-field" required>
</div>
<div class="flex items-center gap-2">
<input type="checkbox" name="remember" id="remember" class="rounded">
<label for="remember" class="text-sm text-slate-400">Remember me</label>
</div>
<button type="submit" class="btn-primary">Sign In</button>
</form>
<p class="text-center mt-6 text-sm text-slate-400">Don't have an account? <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 font-medium">Create one free</a></p>
@endsection
