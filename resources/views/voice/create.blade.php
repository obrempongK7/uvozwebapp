@extends('layouts.app')
@section('title','New Voice Post')
@section('content')
<div class="max-w-2xl mx-auto">
<div class="mb-6"><h1 class="text-2xl font-bold text-white">Create Voice Post</h1><p class="text-slate-400 text-sm mt-1">Share your voice with the world and earn points</p></div>
<div class="card p-6">
<form action="{{ route('voice.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
@csrf
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Title *</label>
<input type="text" name="title" value="{{ old('title') }}" placeholder="Give your voice post a catchy title..." class="input-field" required maxlength="200">
@error('title')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
<textarea name="description" rows="3" placeholder="Tell listeners what this is about..." class="input-field resize-none" maxlength="2000">{{ old('description') }}</textarea>
</div>
<div x-data="{ fileName: null, dragging: false }">
<label class="block text-sm font-medium text-slate-300 mb-2">Audio File * <span class="text-slate-500">(MP3, M4A, AAC, WAV · Max 50MB)</span></label>
<div class="border-2 border-dashed border-dark-border rounded-2xl p-8 text-center transition-all duration-300 cursor-pointer"
     :class="dragging ? 'border-purple-500 bg-purple-500/10' : 'hover:border-slate-500'"
     @dragover.prevent="dragging=true" @dragleave="dragging=false"
     @drop.prevent="dragging=false; fileName=$event.dataTransfer.files[0]?.name; $refs.audioInput.files=$event.dataTransfer.files"
     @click="$refs.audioInput.click()">
<svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
<p class="text-white font-medium" x-text="fileName || 'Drag & drop or click to upload'"></p>
<p class="text-slate-500 text-sm mt-1">Supports MP3, M4A, AAC, WAV</p>
<input type="file" name="audio" x-ref="audioInput" class="hidden" accept=".mp3,.m4a,.aac,.wav" required @change="fileName=$event.target.files[0]?.name">
</div>
@error('audio')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
</div>
<div x-data="{ coverUrl: null }">
<label class="block text-sm font-medium text-slate-300 mb-2">Cover Image <span class="text-slate-500">(optional)</span></label>
<div class="flex items-center gap-4">
<div class="w-20 h-20 rounded-xl bg-dark-border flex items-center justify-center overflow-hidden flex-shrink-0">
<img x-show="coverUrl" :src="coverUrl" class="w-full h-full object-cover">
<svg x-show="!coverUrl" class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
</div>
<input type="file" name="cover" accept="image/*" class="input-field" @change="coverUrl=URL.createObjectURL($event.target.files[0])">
</div>
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Hashtags</label>
<input type="text" name="hashtags" value="{{ old('hashtags') }}" placeholder="#voice #creator #motivation" class="input-field">
<p class="text-xs text-slate-500 mt-1">Separate with spaces</p>
</div>
<div>
<label class="block text-sm font-medium text-slate-300 mb-2">Visibility</label>
<select name="visibility" class="input-field">
<option value="public" {{ old('visibility')=='public'?'selected':'' }}>🌍 Public</option>
<option value="followers" {{ old('visibility')=='followers'?'selected':'' }}>👥 Followers Only</option>
<option value="private" {{ old('visibility')=='private'?'selected':'' }}>🔒 Private</option>
</select>
</div>
<div class="flex items-center gap-4 pt-2">
<button type="submit" class="btn-primary">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
Publish Voice Post
</button>
<a href="{{ route('voice.index') }}" class="btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
