@if ($paginator->hasPages())
    <div class="px-4 py-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between text-xs">

        {{-- Left: info --}}
        <div class="text-base-content/60">
            @if (method_exists($paginator, 'total') && $paginator->total())
                Showing
                <span class="font-semibold">
                    {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
                </span>
                of
                <span class="font-semibold">{{ $paginator->total() }}</span>
                result{{ $paginator->total() === 1 ? '' : 's' }}.
            @else
                Page
                <span class="font-semibold">{{ $paginator->currentPage() }}</span>
            @endif
        </div>

        {{-- Right: pagination --}}
        <nav class="inline-flex items-center gap-1" role="navigation" aria-label="Pagination Navigation">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <button
                    type="button"
                    class="btn btn-xs btn-ghost btn-disabled opacity-50 cursor-not-allowed"
                    disabled
                >
                    ‹ Prev
                </button>
            @else
                <button
                    type="button"
                    class="btn btn-xs btn-ghost"
                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    wire:loading.attr="disabled"
                    rel="prev"
                >
                    ‹ Prev
                </button>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                {{-- "Three dots" separator --}}
                @if (is_string($element))
                    <span class="px-1 select-none">…</span>
                @endif

                {{-- Array of page links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button
                                type="button"
                                class="btn btn-xs btn-primary"
                                aria-current="page"
                            >
                                {{ $page }}
                            </button>
                        @else
                            <button
                                type="button"
                                class="btn btn-xs btn-ghost"
                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                wire:loading.attr="disabled"
                            >
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <button
                    type="button"
                    class="btn btn-xs btn-ghost"
                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    wire:loading.attr="disabled"
                    rel="next"
                >
                    Next ›
                </button>
            @else
                <button
                    type="button"
                    class="btn btn-xs btn-ghost btn-disabled opacity-50 cursor-not-allowed"
                    disabled
                >
                    Next ›
                </button>
            @endif
        </nav>
    </div>
@endif
