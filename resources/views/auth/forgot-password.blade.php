@extends('layouts.auth')
@section('title','Reset Password')
@section('content')
<h2 class="text-2xl font-bold text-white mb-2">Forgot Password?</h2>
<p class="text-slate-400 text-sm mb-6">Enter your email and we'll send a reset link.</p>
<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
@csrf
<input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" class="input-field" required>
@error('email')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
<button type="submit" class="btn-primary">Send Reset Link</button>
</form>
<p class="text-center mt-4 text-sm text-slate-400"><a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300">Back to login</a></p>
@endsection
