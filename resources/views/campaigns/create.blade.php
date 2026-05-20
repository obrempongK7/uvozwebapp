@extends('layouts.app')
@section('title','Create Campaign')
@section('content')
<div class="max-w-2xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-6">Create Advertising Campaign</h1>
<div class="card p-6">
<form action="{{ route('campaign.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
@csrf
<div><label class="block text-sm font-medium text-slate-300 mb-2">Campaign Title *</label><input type="text" name="title" class="input-field" required placeholder="Engaging campaign name..."></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Description</label><textarea name="description" rows="3" class="input-field resize-none" placeholder="What do you want users to do?"></textarea></div>
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">Campaign Type *</label>
<select name="type" class="input-field">@foreach(['voice_listen'=>'Voice Listens','status_view'=>'Status Views','link_click'=>'Link Clicks','app_install'=>'App Installs','survey'=>'Surveys'] as $v=>$l)<option value="{{ $v }}">{{ $l }}</option>@endforeach</select></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Target URL</label><input type="url" name="target_url" class="input-field" placeholder="https://..."></div>
</div>
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">Total Budget (USD) *</label><input type="number" name="budget" step="0.01" min="10" class="input-field" required></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Points Per Action *</label><input type="number" name="points_per_action" min="1" class="input-field" required value="10"></div>
</div>
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">Start Date</label><input type="datetime-local" name="starts_at" class="input-field"></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">End Date</label><input type="datetime-local" name="ends_at" class="input-field"></div>
</div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Cover Image</label><input type="file" name="cover_image" accept="image/*" class="input-field"></div>
<div class="flex gap-4 pt-2">
<button type="submit" class="btn-primary">Submit Campaign</button>
<a href="{{ route('campaign.mine') }}" class="btn-secondary">My Campaigns</a>
</div>
</form>
</div>
</div>
@endsection
