@extends('layouts.auth')
@section('title','New Password')
@section('content')
<h2 class="text-2xl font-bold text-white mb-6">Set new password</h2>
<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
@csrf
<input type="hidden" name="token" value="{{ $token }}">
<input type="hidden" name="email" value="{{ $email }}">
<input type="password" name="password" placeholder="New password" class="input-field" required>
@error('password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
<input type="password" name="password_confirmation" placeholder="Confirm password" class="input-field" required>
<button type="submit" class="btn-primary">Update Password</button>
</form>
@endsection
