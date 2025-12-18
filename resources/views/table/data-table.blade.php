<div class="space-y-4">
    {{-- Card wrapper --}}
    <div class="card bg-base-100 px-2 shadow-sm border border-base-300/60">
        {{-- Card header / toolbar --}}
        <div
            class="border-b border-base-300/60 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between bg-linear-to-r from-base-100 to-base-200/80">
            <div class="space-y-0.5">
                <h2 class="text-xl md:text-2xl font-semibold tracking-tight text-base-content">
                    {{ $title }}
                </h2>
                <p class="text-xs text-base-content/60">
                    Showing
                    <span class="font-semibold text-base-content">
                        {{ $rows->count() ? $rows->firstItem() . '–' . $rows->lastItem() : 0 }}
                    </span>
                    of
                    <span class="font-semibold text-base-content">{{ $rows->total() }}</span>
                    result{{ $rows->total() === 1 ? '' : 's' }}.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 justify-end">
                {{-- Search --}}
                <label class="input input-bordered input-sm flex items-center gap-2 w-full sm:w-64 bg-base-100/80">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M10.5 3.75a6.75 6.75 0 1 0 4.2 12.06l3.72 3.72a.75.75 0 1 0 1.06-1.06l-3.72-3.72a6.75 6.75 0 0 0-5.26-11zM5.25 10.5a5.25 5.25 0 1 1 10.5 0 5.25 5.25 0 0 1-10.5 0z"
                            clip-rule="evenodd" />
                    </svg>
                    <input type="text" class="grow" placeholder="Search…" wire:model.live.debounce.300ms="q" />
                </label>

                {{-- Per page --}}
                <select class="select select-bordered select-sm w-full sm:w-auto bg-base-100/80"
                    wire:model.live.debounce="perPage">
                    @foreach ($this->perPageOptions as $n)
                        <option value="{{ $n }}">{{ $n }} / page</option>
                    @endforeach
                </select>

                {{-- Reset --}}
                <button class="btn btn-sm btn-ghost gap-1" wire:click="clearFilters">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 4h7V2L20 5.5 11 9V7H6v4H4V4Zm1 9h2v5h5v2H5v-7Zm9 7v-2h5v-5h2v7h-7Z" />
                    </svg>
                    <span class="hidden sm:inline">Reset</span>
                </button>

                {{-- Export --}}
                <div class="join">
                    <button title="EXCEL Report" wire:loading.class="opacity-50 cursor-wait"
                        wire:target="export('xlsx')" class="btn btn-sm btn-success join-item gap-1"
                        wire:click="export('xlsx')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 20h14v-2H5v2Zm7-3 5-6h-3V4h-4v7H7l5 6Z" />
                        </svg>
                        <span class="hidden sm:inline">Excel</span>
                        <span wire:loading wire:target="export('xlsx')"
                            class="loading loading-spinner loading-xs"></span>
                    </button>

                    <button title="PDF Report" wire:loading.class="opacity-50 cursor-wait" wire:target="export('pdf')"
                        class="btn btn-sm btn-error ms-2 join-item gap-1" wire:click="export('pdf')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 20h14v-2H5v2Zm7-3 5-6h-3V4h-4v7H7l5 6Z" />
                        </svg>
                        <span class="hidden sm:inline">PDF</span>
                        <span wire:loading wire:target="export('pdf')"
                            class="loading loading-spinner loading-xs"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Optional date toolbar --}}
        @if ($sortbydate)
            <div class="px-4 pt-3 pb-2 border-b border-base-300/60 bg-base-100/90">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-base-content/70 uppercase tracking-wide">
                        Date filter
                    </span>

                    <select class="select select-bordered select-sm bg-base-100" wire:model.live="dateField"
                        title="Filter by which timestamp">
                        @foreach ($dateFields as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    <input type="date" class="input input-bordered input-sm bg-base-100" wire:model.live="dateFrom"
                        title="From date" />

                    <span class="text-xs text-base-content/60">to</span>

                    <input type="date" class="input input-bordered input-sm bg-base-100" wire:model.live="dateTo"
                        title="To date" />
                </div>
            </div>
        @endif

        {{-- Table with horizontal scroll --}}
        <div class="card-body p-0" x-data="{ selected: [] }">
            <div class="overflow-x-auto w-full">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-x-auto shadow-sm">
                        <table class="table table-sm w-full min-w-full">
                            <thead class="bg-base-100 sticky top-0 z-20">
                                {{-- Header --}}
                                <tr class="bg-linear-to-r from-base-200 to-base-300/90 backdrop-blur">

                                    @if ($allowselection)
                                        <th>

                                            <input title="check all" type="checkbox" x-model="selectAll"
                                                @change="selected = $event.target.checked ? @json($rows->pluck('id')) : []"
                                                class="checkbox checkbox-xs checkbox-primary" />


                                        </th>
                                    @endif
                                    <th class="px-3 py-2 text-[11px] font-semibold text-base-content/70 w-10">
                                        <span class="hidden sm:inline">#</span>
                                    </th>
                                    @foreach ($columns as $c)
                                        @php
                                            $active = $sortField === ($c['field'] ?? '');
                                            $sortable = $c['sortable'] ?? true;
                                            $hideSm = $c['hide_sm'] ?? false;
                                        @endphp
                                        <th
                                            class="px-3 py-2 text-[11px] uppercase tracking-wide text-base-content/60 {{ $hideSm ? 'hidden sm:table-cell' : '' }}">
                                            @if ($sortable)
                                                <button
                                                    class="btn btn-ghost btn-xs px-1 normal-case font-semibold text-base-content/80 hover:text-base-content whitespace-nowrap"
                                                    wire:click="sortBy('{{ $c['field'] }}')">
                                                    <span>{{ $c['label'] ?? ucfirst($c['field']) }}</span>
                                                    @if ($active)
                                                        <span class="ml-1 text-[10px]">
                                                            {{ $sortDirection === 'asc' ? '▲' : '▼' }}
                                                        </span>
                                                    @endif
                                                </button>
                                            @else
                                                <span class="font-semibold text-base-content/80 whitespace-nowrap">
                                                    {{ $c['label'] ?? ucfirst($c['field']) }}
                                                </span>
                                            @endif
                                        </th>
                                    @endforeach

                                    @if ($allowactios)
                                        <th
                                            class="px-3 py-2 text-[11px] font-semibold text-base-content/70 hidden sm:table-cell text-right whitespace-nowrap">
                                            Actions
                                        </th>
                                    @endif
                                </tr>

                                {{-- Filters row (desktop) --}}
                                <tr class="hidden sm:table-row bg-base-100/95 border-b border-base-200/80">

                                    {{-- uset to push the filters  --}}
                                    <th class="px-3 py-2"></th>
                                    @if ($allowselection)
                                        <th class="px-3 py-2"></th>

                                    @endif

                                    @foreach ($columns as $c)
                                        @php
                                            $field = $c['field'];
                                            $ctype = $c['type'] ?? 'text';
                                            $ftype =
                                                $c['filter'] ??
                                                ($ctype === 'boolean'
                                                    ? 'boolean'
                                                    : ($ctype === 'date'
                                                        ? 'none'
                                                        : 'text'));
                                            $bind = $c['filter_key'] ?? str_replace('.', '__', $field);
                                            $hideSm = $c['hide_sm'] ?? false;
                                        @endphp
                                        <th class="px-3 py-2 {{ $hideSm ? 'hidden sm:table-cell' : '' }}">
                                            @if ($ftype === 'text')
                                                <input type="text"
                                                    class="input input-bordered input-xs w-full bg-base-100/90"
                                                    placeholder="Filter {{ $c['label'] ?? $field }}"
                                                    wire:model.live.debounce.300ms="filters.{{ $bind }}" />
                                            @elseif ($ftype === 'boolean')
                                                <select class="select select-bordered select-xs w-full bg-base-100/90"
                                                    wire:model.live.debounce="filters.{{ $bind }}">
                                                    <option value="">All</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            @elseif ($ftype === 'select')
                                                <select class="select select-bordered select-xs w-full bg-base-100/90"
                                                    wire:model.live.debounce.150ms="filters.{{ $bind }}">
                                                    <option value="">All</option>
                                                    @foreach ($c['options'] ?? [] as $val => $label)
                                                        <option value="{{ $val }}">{{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @elseif ($ftype === 'date-range')
                                                <div class="flex items-center gap-1">
                                                    <select class="select select-bordered select-xs bg-base-100/90"
                                                        wire:model.live="dateField" title="Filter by field">
                                                        @foreach ($dateFields as $key => $label)
                                                            <option value="{{ $key }}">{{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <input type="date"
                                                        class="input input-bordered input-xs bg-base-100/90"
                                                        wire:model.live="dateFrom" title="From date" />

                                                    <span class="text-[11px] text-base-content/60">→</span>

                                                    <input type="date"
                                                        class="input input-bordered input-xs bg-base-100/90"
                                                        wire:model.live="dateTo" title="To date" />

                                                    <button class="btn btn-ghost btn-xs" wire:click="clearDateFilter"
                                                        title="Clear date filter">
                                                        ✕
                                                    </button>
                                                </div>
                                            @endif
                                        </th>
                                    @endforeach

                                    @if ($allowactios)
                                        <th class="px-3 py-2 hidden sm:table-cell"></th>
                                    @endif
                                </tr>
                            </thead>

                            @forelse ($rows as $r)
                                {{-- One tbody per row (stable scopes) --}}
                                <tbody x-data="{ open: false, expanded: {} }" wire:key="row-{{ $r->id }}" class="text-sm">
                                    <tr

                                        class="align-top transition-colors hover:bg-base-200/70 {{ $loop->odd ? 'row-even' : '' }}">


                                        @if ($allowselection)
                                            <td>
                                                <input x-model="selected" type="checkbox"
                                                    class="checkbox checkbox-info checkbox-xs"
                                                    :value="{{ $r->id }}" />

                                            </td>
                                        @endif

                                        <td class="px-2 py-2 w-10">
                                            <button class="sm:hidden btn btn-ghost btn-xs p-0" @click="open = !open"
                                                :aria-expanded="open.toString()" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4 transition-transform"
                                                    :class="open ? 'rotate-90' : ''" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <span class="hidden sm:inline mt-4 text-base-content/70 text-xs">
                                                {{ ($rows->firstItem() ?? 0) + $loop->iteration - 1 }}
                                            </span>
                                        </td>

                                        {{-- Data cells --}}
                                        @foreach ($columns as $c)
                                            @php
                                                $field = $c['field'];
                                                $type = $c['type'] ?? 'text';
                                                $val = data_get($r, $field);
                                                $hideSm = $c['hide_sm'] ?? false;
                                                $width = $c['width'] ?? 'max-w-xs';
                                                $wordLimit = $c['word_limit'] ?? null;
                                                $cellId = 'cell-' . $r->id . '-' . str_replace('.', '-', $field);

                                                // Format value
                                                if ($type === 'date' && !empty($c['format']) && $val) {
                                                    $displayVal = \Illuminate\Support\Carbon::parse($val)->format(
                                                        $c['format'],
                                                    );
                                                } else {
                                                    $displayVal = $val;
                                                }

                                                // Word limiting logic
                                                $needsTruncation = false;
                                                $truncatedVal = $displayVal;
                                                $fullVal = $displayVal;

                                                if ($wordLimit && is_string($displayVal)) {
                                                    $words = explode(' ', $displayVal);
                                                    if (count($words) > $wordLimit) {
                                                        $needsTruncation = true;
                                                        $truncatedVal =
                                                            implode(' ', array_slice($words, 0, $wordLimit)) . '...';
                                                        $fullVal = $displayVal;
                                                    }
                                                }
                                            @endphp
                                            <td
                                                class="px-3 py-2 text-xs {{ $hideSm ? 'hidden sm:table-cell' : '' }} {{ $width }}">
                                                @if ($needsTruncation)
                                                    <div x-data="{ expanded: false }" class="wrap-break">
                                                        <span x-show="!expanded" x-cloak>
                                                            {{ e($truncatedVal) }}
                                                        </span>
                                                        <span x-show="expanded" x-cloak class="whitespace-normal">
                                                            {{ e($fullVal) }}
                                                        </span>
                                                        <button @click="expanded = !expanded" type="button"
                                                            class="text-primary hover:text-primary-focus font-medium ml-1 text-[11px] underline"
                                                            x-text="expanded ? 'Show less' : 'Show more'">
                                                        </button>
                                                    </div>
                                                @else
                                                    <span
                                                        class="wrap-break whitespace-normal">{{ e($displayVal) }}</span>
                                                @endif
                                            </td>
                                        @endforeach

                                        {{-- Actions (desktop) --}}
                                        @if ($allowactios)
                                            <td class="px-3 py-2 whitespace-nowrap hidden sm:table-cell">
                                                @include($actionpath, ['r' => $r])
                                            </td>
                                        @endif
                                    </tr>

                                    {{-- Mobile details --}}
                                    <tr x-show="open" x-cloak x-transition
                                        class="sm:hidden {{ $loop->odd ? 'row-even' : '' }}">
                                        <td colspan="{{ count($columns) + ($allowactios ? 2 : 1) }}"
                                            class="px-4 pb-3">
                                            <div
                                                class="rounded-xl border border-base-300/60 p-3 bg-base-200/40 space-y-3">
                                                <dl class="space-y-2">
                                                    @foreach ($columns as $c)
                                                        @php
                                                            $field = $c['field'];
                                                            $type = $c['type'] ?? 'text';
                                                            $val = data_get($r, $field);

                                                            if ($type === 'date' && !empty($c['format']) && $val) {
                                                                $displayVal = \Illuminate\Support\Carbon::parse(
                                                                    $val,
                                                                )->format($c['format']);
                                                            } else {
                                                                $displayVal = $val;
                                                            }

                                                            $needsTruncation = false;
                                                            $truncatedVal = $displayVal;
                                                            $fullVal = $displayVal;

                                                        @endphp
                                                        <div>
                                                            <dt
                                                                class="text-[11px] uppercase tracking-wide text-base-content/60">
                                                                {{ $c['label'] ?? ucfirst($field) }}
                                                            </dt>
                                                            <dd class="text-sm">
                                                                @if ($needsTruncation)
                                                                    <div x-data="{ mobileExpanded: false }">
                                                                        <span x-show="!mobileExpanded">
                                                                            {{ e($truncatedVal) }}
                                                                        </span>
                                                                        <span x-show="mobileExpanded" x-cloak>
                                                                            {{ e($fullVal) }}
                                                                        </span>
                                                                        <button
                                                                            @click="mobileExpanded = !mobileExpanded"
                                                                            type="button"
                                                                            class="text-primary hover:text-primary-focus font-medium ml-1 text-[11px] underline"
                                                                            x-text="mobileExpanded ? 'Show less' : 'Show more'">
                                                                        </button>
                                                                    </div>
                                                                @else
                                                                    {{ e($displayVal) }}
                                                                @endif
                                                            </dd>
                                                        </div>
                                                    @endforeach
                                                </dl>

                                                @if ($allowactios)
                                                    <div class="flex flex-wrap gap-1 justify-start">
                                                        @include($actionpath, ['r' => $r])
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @empty
                                <tbody>
                                    <tr>
                                        <td colspan="{{ count($columns) + ($allowactios ? 2 : 1) }}">
                                            <div class="p-6 text-center text-base-content/60 text-sm">
                                                No results found. Try changing filters or search terms.
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}

            @if ($allowselection)
                <div class="flex mx-3 gap-2 mt-1">
                    <div>
                        <input title="check all" type="checkbox" x-model="selectAll"
                            @change="selected = $event.target.checked ? @json($rows->pluck('id')) : []"
                            class="checkbox checkbox-xs checkbox-primary" />
                        <label for="selectAll" class="lable text-md">check all</label>
                    </div>

                    @include($allowselectionpath)
                   
                </div>
            @endif
                <div
                    class="px-4 py-3 border-t border-base-300/60 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-xs text-base-content/60">
                        Page <span class="font-semibold">{{ $rows->currentPage() }}</span>
                        of <span class="font-semibold">{{ $rows->lastPage() }}</span>
                    </div>
                    <div>
                        {{ $rows->onEachSide(1)->links() }}
                    </div>
                </div>
        </div>
    </div>
</div>
