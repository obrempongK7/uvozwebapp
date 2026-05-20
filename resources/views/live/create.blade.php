@extends('layouts.app')
@section('title','Create Live Room')
@section('content')
<div class="max-w-2xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-6">Start a Live Room</h1>
<div class="card p-6">
<form action="{{ route('live.store') }}" method="POST" class="space-y-5">
@csrf
<div><label class="block text-sm font-medium text-slate-300 mb-2">Room Title *</label><input type="text" name="title" class="input-field" required placeholder="What's the discussion about?"></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Description</label><textarea name="description" rows="3" class="input-field resize-none"></textarea></div>
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">Room Type</label>
<select name="type" class="input-field"><option value="public">🌍 Public</option><option value="private">🔒 Private</option><option value="password_protected">🔑 Password Protected</option></select></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Max Participants</label><input type="number" name="max_participants" min="2" class="input-field" placeholder="Unlimited"></div>
</div>
<div x-data="{ ticketed: false }">
<div class="flex items-center gap-3 mb-3">
<label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" name="is_ticketed" value="1" x-model="ticketed" class="sr-only peer"><div class="w-11 h-6 bg-dark-border peer-checked:bg-purple-600 rounded-full peer transition-colors"></div><div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div></label>
<span class="text-sm text-slate-300">Ticketed Entry</span>
</div>
<div x-show="ticketed"><label class="block text-sm font-medium text-slate-300 mb-2">Ticket Price (USD)</label><input type="number" name="ticket_price" step="0.01" min="0.5" class="input-field"></div>
</div>
<div x-data="{ scheduled: false }">
<div class="flex items-center gap-3 mb-3">
<label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" x-model="scheduled" class="sr-only peer"><div class="w-11 h-6 bg-dark-border peer-checked:bg-purple-600 rounded-full peer transition-colors"></div><div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div></label>
<span class="text-sm text-slate-300">Schedule for later</span>
</div>
<div x-show="scheduled"><input type="datetime-local" name="scheduled_at" class="input-field"><input type="hidden" name="schedule" value="1" x-show="scheduled"></div>
</div>
<div class="flex gap-4 pt-2">
<button type="submit" class="btn-primary">🎤 Start Room</button>
<a href="{{ route('live.index') }}" class="btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
