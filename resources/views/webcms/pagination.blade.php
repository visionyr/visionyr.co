@if ($paginator->hasPages())
    @php
        $linkClasses = 'grid h-9 min-w-9 place-items-center rounded-lg px-2.5 text-sm transition-colors';
        $idleClasses = $linkClasses.' text-ink/60 hover:bg-steel/60 hover:text-navy';
        $activeClasses = $linkClasses.' bg-navy font-medium text-white';
        $disabledClasses = $linkClasses.' cursor-not-allowed text-ink/25';
    @endphp

    <nav class="flex flex-wrap items-center justify-between gap-3" role="navigation" aria-label="Pagination">
        <p class="text-xs text-ink/50">
            Showing {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} of {{ number_format($paginator->total()) }}
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="{{ $disabledClasses }}" aria-disabled="true" aria-label="Previous page">
                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $idleClasses }}" aria-label="Previous page">
                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="{{ $disabledClasses }}">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="{{ $activeClasses }}" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="{{ $idleClasses }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $idleClasses }}" aria-label="Next page">
                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                </a>
            @else
                <span class="{{ $disabledClasses }}" aria-disabled="true" aria-label="Next page">
                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
