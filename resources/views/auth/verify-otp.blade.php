@extends('layouts.auth')
@section('title','Verify Email')
@section('content')
<div class="text-center mb-6">
    <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
    </div>
    <h2 class="text-2xl font-bold text-white">Verify your email</h2>
    <p class="text-slate-400 text-sm mt-2">We sent a 6-digit code to <strong class="text-white">{{ auth()->user()->email }}</strong></p>
</div>
<form method="POST" action="{{ route('otp.verify') }}" class="space-y-4">
    @csrf
    <div>
        <input type="text" name="otp" placeholder="Enter 6-digit code" class="input-field text-center text-2xl tracking-widest font-bold" maxlength="6" required autofocus>
        @error('otp')<p class="mt-1 text-xs text-red-400 text-center">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="btn-primary">Verify Email</button>
</form>
<p class="text-center mt-4 text-sm text-slate-400">
    Didn't receive it?
    <form action="{{ route('otp.resend') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="text-purple-400 hover:text-purple-300 font-medium">Resend code</button>
    </form>
</p>
@endsection
