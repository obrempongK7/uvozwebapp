@extends('layouts.app')
@section('title','Create Podcast')
@section('content')
<div class="max-w-2xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-6">Create Podcast Show</h1>
<div class="card p-6">
<form action="{{ route('podcast.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
@csrf
<div><label class="block text-sm font-medium text-slate-300 mb-2">Show Title *</label><input type="text" name="title" class="input-field" required></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Description</label><textarea name="description" rows="3" class="input-field resize-none"></textarea></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Cover Artwork</label><input type="file" name="cover_artwork" accept="image/*" class="input-field"></div>
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">Category</label>
<select name="category" class="input-field">@foreach(['Business','Technology','Entertainment','Education','Health','News','Comedy','Sports','True Crime','Society'] as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach</select></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Type</label>
<select name="type" class="input-field"><option value="free">Free</option><option value="premium">Premium Subscription</option><option value="paid">Paid</option></select></div>
</div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Language</label>
<select name="language" class="input-field"><option value="en">English</option><option value="fr">French</option><option value="es">Spanish</option><option value="pt">Portuguese</option><option value="ar">Arabic</option><option value="sw">Swahili</option></select></div>
<div class="flex gap-4 pt-2">
<button type="submit" class="btn-primary">Create Podcast</button>
<a href="{{ route('podcast.index') }}" class="btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
