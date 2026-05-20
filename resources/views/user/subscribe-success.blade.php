@extends('layouts.app')
@section('title','Subscription Active')
@section('content')
<div class="max-w-lg mx-auto text-center py-12">
<div class="w-20 h-20 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">🎉</div>
<h1 class="text-3xl font-black text-white mb-4">You're all set!</h1>
<p class="text-slate-400 mb-8">Your subscription is now active. Start earning more today!</p>
<div class="flex gap-4 justify-center">
<a href="{{ route('dashboard') }}" class="btn-primary">Go to Dashboard</a>
<a href="{{ route('voice.create') }}" class="btn-secondary">Create Voice Post</a>
</div>
</div>
@endsection
