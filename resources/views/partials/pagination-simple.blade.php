@if ($paginator->hasPages())
<div class="flex items-center justify-between mt-6">
@if ($paginator->onFirstPage())
<span class="btn-secondary py-2 px-4 text-sm opacity-50 cursor-not-allowed">← Previous</span>
@else
<a href="{{ $paginator->previousPageUrl() }}" class="btn-secondary py-2 px-4 text-sm">← Previous</a>
@endif
@if ($paginator->hasMorePages())
<a href="{{ $paginator->nextPageUrl() }}" class="btn-primary py-2 px-4 text-sm">Next →</a>
@else
<span class="btn-primary py-2 px-4 text-sm opacity-50 cursor-not-allowed">Next →</span>
@endif
</div>
@endif
