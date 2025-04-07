@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <div class="flex justify-between">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 rounded text-gray-400 cursor-not-allowed">
                    &laquo;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1 rounded text-indigo-600 hover:text-indigo-800">
                    &laquo;
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-1 rounded text-indigo-600 hover:text-indigo-800">
                    &raquo;
                </a>
            @else
                <span class="px-3 py-1 rounded text-gray-400 cursor-not-allowed">
                    &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif