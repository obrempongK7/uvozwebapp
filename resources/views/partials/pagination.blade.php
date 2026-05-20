@if ($paginator->hasPages())
<div class="flex items-center justify-center gap-1 mt-6">
@if ($paginator->onFirstPage())
<span class="px-3 py-2 rounded-xl text-sm text-slate-600 cursor-not-allowed">←</span>
@else
<a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 rounded-xl text-sm text-slate-400 hover:text-white hover:bg-dark-border transition-colors">←</a>
@endif
@foreach ($elements as $element)
@if (is_string($element))
<span class="px-3 py-2 rounded-xl text-sm text-slate-600">{{ $element }}</span>
@endif
@if (is_array($element))
@foreach ($element as $page => $url)
@if ($page == $paginator->currentPage())
<span class="px-3 py-2 rounded-xl text-sm font-semibold text-white gradient-bg">{{ $page }}</span>
@else
<a href="{{ $url }}" class="px-3 py-2 rounded-xl text-sm text-slate-400 hover:text-white hover:bg-dark-border transition-colors">{{ $page }}</a>
@endif
@endforeach
@endif
@endforeach
@if ($paginator->hasMorePages())
<a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 rounded-xl text-sm text-slate-400 hover:text-white hover:bg-dark-border transition-colors">→</a>
@else
<span class="px-3 py-2 rounded-xl text-sm text-slate-600 cursor-not-allowed">→</span>
@endif
</div>
@endif
