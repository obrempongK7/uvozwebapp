@extends('layouts.auth')
@section('title','Two-Factor Auth')
@section('content')
<h2 class="text-2xl font-bold text-white mb-2">Two-Factor Authentication</h2>
<p class="text-slate-400 text-sm mb-6">Enter the code from your authenticator app.</p>
<form method="POST" action="{{ route('two-factor') }}" class="space-y-4">
@csrf
<input type="text" name="code" placeholder="6-digit code" class="input-field text-center text-2xl tracking-widest font-bold" maxlength="6" required>
@error('code')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
<button type="submit" class="btn-primary">Verify</button>
</form>
@endsection
