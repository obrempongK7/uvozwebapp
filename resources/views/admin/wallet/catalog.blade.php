@extends('layouts.admin')
@section('page_title','Reward Catalog')
@section('content')
<div class="flex items-center justify-between mb-4">
<p class="text-slate-400 text-sm">{{ $rewards->total() }} rewards</p>
<button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-primary text-sm py-2 px-4">+ Add Reward</button>
</div>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
@forelse($rewards as $r)
<div class="card p-4">
@if($r->image)<img src="{{ $r->image_url }}" class="w-full h-32 object-cover rounded-2xl mb-3">@endif
<h3 class="font-semibold text-white mb-1">{{ $r->title }}</h3>
<div class="flex items-center justify-between mb-2">
<span class="badge badge-amber text-xs">{{ ucfirst($r->category) }}</span>
<span class="text-sm font-bold text-amber-400">{{ number_format($r->points_required) }} pts</span>
</div>
<p class="text-xs text-slate-400">Stock: {{ $r->stock_quantity ?? '∞' }} · {{ ucfirst($r->delivery_method) }}</p>
<div class="flex gap-2 mt-3">
@if($r->is_active)<span class="badge-active text-xs">Active</span>@else<span class="badge-rejected text-xs">Inactive</span>@endif
<form action="{{ route('admin.wallet.catalog.delete',$r) }}" method="POST" class="inline ml-auto" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-red-400 hover:text-red-300">Delete</button></form>
</div>
</div>
@empty<div class="md:col-span-3 card p-8 text-center"><p class="text-slate-400">No rewards yet. Add your first reward!</p></div>@endforelse
</div>
{{ $rewards->links() }}
<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
<div class="card p-6 w-full max-w-lg">
<div class="flex items-center justify-between mb-4"><h3 class="font-bold text-white">Add Reward</h3><button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-white">✕</button></div>
<form action="{{ route('admin.wallet.catalog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
@csrf
<div><label class="block text-xs text-slate-400 mb-1.5">Title *</label><input type="text" name="title" class="input-field" required></div>
<div class="grid sm:grid-cols-2 gap-3">
<div><label class="block text-xs text-slate-400 mb-1.5">Category</label><select name="category" class="input-field">@foreach(['cash','airtime','data','electronics','travel','gift_cards','vouchers','tickets','merchandise'] as $c)<option value="{{ $c }}">{{ ucfirst(str_replace('_',' ',$c)) }}</option>@endforeach</select></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Points Required</label><input type="number" name="points_required" min="1" class="input-field" required></div>
</div>
<div class="grid sm:grid-cols-2 gap-3">
<div><label class="block text-xs text-slate-400 mb-1.5">USD Value</label><input type="number" name="usd_value" step="0.01" class="input-field"></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Delivery</label><select name="delivery_method" class="input-field"><option value="digital">Digital</option><option value="physical">Physical</option><option value="automatic">Automatic</option></select></div>
</div>
<div><label class="block text-xs text-slate-400 mb-1.5">Image</label><input type="file" name="image" accept="image/*" class="input-field"></div>
<div class="flex gap-3"><button type="submit" class="btn-primary flex-1 justify-center">Add Reward</button><button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="btn-secondary flex-1 justify-center">Cancel</button></div>
</form>
</div>
</div>
@endsection
