@extends('layouts.app')
@section('title','Edit Voice Post')
@section('content')
<div class="max-w-2xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-6">Edit Voice Post</h1>
<div class="card p-6">
<form action="{{ route('voice.update', $voicePost) }}" method="POST" class="space-y-5">
@csrf @method('PUT')
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Title *</label>
<input type="text" name="title" value="{{ old('title', $voicePost->title) }}" class="input-field" required maxlength="200">
@error('title')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
<textarea name="description" rows="3" class="input-field resize-none" maxlength="2000">{{ old('description', $voicePost->description) }}</textarea>
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Visibility</label>
<select name="visibility" class="input-field">
<option value="public" {{ $voicePost->visibility=='public'?'selected':'' }}>🌍 Public</option>
<option value="followers" {{ $voicePost->visibility=='followers'?'selected':'' }}>👥 Followers Only</option>
<option value="private" {{ $voicePost->visibility=='private'?'selected':'' }}>🔒 Private</option>
</select>
</div>
<div class="p-4 rounded-2xl bg-dark-border">
<p class="text-xs text-slate-400 mb-2">Current Audio</p>
<audio controls class="w-full" style="filter:invert(1) hue-rotate(180deg);">
<source src="{{ $voicePost->audio_url }}" type="audio/mpeg">
</audio>
</div>
<div class="flex gap-4 pt-2">
<button type="submit" class="btn-primary">Save Changes</button>
<a href="{{ route('voice.show', $voicePost) }}" class="btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
