<div class="space-y-4 table-scroll">
    {{-- Card wrapper --}}
    <div class="card bg-base-100 px-2 shadow-sm border border-base-300/60">
        {{-- Card header / toolbar --}}
        <div
            class="border-b border-base-300/60 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between bg-gradient-to-r from-base-100 to-base-200/80">
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
                    <button title="EXCEL Report" class="btn btn-sm btn-success join-item gap-1" wire:click="export('xlsx')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 20h14v-2H5v2Zm7-3 5-6h-3V4h-4v7H7l5 6Z" />
                        </svg>
                        <span class="hidden sm:inline">Excel</span>
                    </button>

                    <button title="PDF Report"  class="btn btn-sm btn-error ms-2 join-item gap-1" wire:click="export('pdf')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 20h14v-2H5v2Zm7-3 5-6h-3V4h-4v7H7l5 6Z" />
                        </svg>
                        <span class="hidden sm:inline">PDF</span>
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

        {{-- Table --}}
        <div class="card-body p-0">
            <div class="overflow-x-auto w-full">
                <table class="table table-sm w-full">
                    <thead class="bg-base-100 sticky top-0 z-20">
                        {{-- Header --}}
                        <tr class="bg-gradient-to-r from-base-200 to-base-300/90 backdrop-blur">
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
                                            class="btn btn-ghost btn-xs px-1 normal-case font-semibold text-base-content/80 hover:text-base-content"
                                            wire:click="sortBy('{{ $c['field'] }}')">
                                            <span>{{ $c['label'] ?? ucfirst($c['field']) }}</span>
                                            @if ($active)
                                                <span class="ml-1 text-[10px]">
                                                    {{ $sortDirection === 'asc' ? '▲' : '▼' }}
                                                </span>
                                            @endif
                                        </button>
                                    @else
                                        <span class="font-semibold text-base-content/80">
                                            {{ $c['label'] ?? ucfirst($c['field']) }}
                                        </span>
                                    @endif
                                </th>
                            @endforeach

                            @if (!empty($this->actions()))
                                <th
                                    class="px-3 py-2 text-[11px] font-semibold text-base-content/70 hidden sm:table-cell text-right">
                                    Actions
                                </th>
                            @endif
                        </tr>

                        {{-- Filters row (desktop) --}}
                        <tr class="hidden sm:table-row bg-base-100/95 border-b border-base-200/80">
                            <th class="px-3 py-2"></th>
                            @foreach ($columns as $c)
                                @php
                                    $field = $c['field'];
                                    $ctype = $c['type'] ?? 'text';
                                    $ftype =
                                        $c['filter'] ??
                                        ($ctype === 'boolean' ? 'boolean' : ($ctype === 'date' ? 'none' : 'text'));
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
                                                <option value="{{ $val }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    @elseif ($ftype === 'date-range')
                                        <div class="flex items-center gap-1">
                                            <select class="select select-bordered select-xs bg-base-100/90"
                                                wire:model.live="dateField" title="Filter by field">
                                                @foreach ($dateFields as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>

                                            <input type="date" class="input input-bordered input-xs bg-base-100/90"
                                                wire:model.live="dateFrom" title="From date" />

                                            <span class="text-[11px] text-base-content/60">→</span>

                                            <input type="date" class="input input-bordered input-xs bg-base-100/90"
                                                wire:model.live="dateTo" title="To date" />

                                            <button class="btn btn-ghost btn-xs" wire:click="clearDateFilter"
                                                title="Clear date filter">
                                                ✕
                                            </button>
                                        </div>
                                    @endif
                                </th>
                            @endforeach

                            @if (!empty($this->actions()))
                                <th class="px-3 py-2 hidden sm:table-cell"></th>
                            @endif
                        </tr>
                    </thead>

                    @forelse ($rows as $r)
                        {{-- One tbody per row (stable scopes) --}}
                        <tbody x-data="{ open: false }" wire:key="row-{{ $r->id }}" class="text-sm">
                            <tr
                                class="align-top transition-colors hover:bg-base-200/70 {{ $loop->odd ? 'row-odd' : 'row-even' }}">
                                {{-- Index / expander --}}
                                <td class="px-2 py-2 w-10">
                                    <button class="sm:hidden btn btn-ghost btn-xs p-0" @click="open = !open"
                                        :aria-expanded="open.toString()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform"
                                            :class="open ? 'rotate-90' : ''" viewBox="0 0 20 20" fill="currentColor">
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
                                    @endphp
                                    <td
                                        class="px-3 py-2 whitespace-normal break-words text-xs {{ $hideSm ? 'hidden sm:table-cell' : '' }} {{ $width }}">
                                        @if ($field === 'status')
                                            @php $s = (string) $r->status; @endphp
                                            <span
                                                class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold
                                                {{ $s === 'open'
                                                    ? 'bg-info/10 text-info'
                                                    : ($s === 'pending'
                                                        ? 'bg-warning/10 text-warning'
                                                        : ($s === 'approved'
                                                            ? 'bg-success/10 text-success'
                                                            : 'bg-error/10 text-error')) }}">
                                                {{ ucfirst($s) }}
                                            </span>
                                        @elseif ($type === 'date' && !empty($c['format']) && $val)
                                            {{ \Illuminate\Support\Carbon::parse($val)->format($c['format']) }}
                                        @else
                                            {{ $val }}
                                        @endif
                                    </td>
                                @endforeach

                                {{-- Actions (desktop) --}}
                                @if (!empty($this->actions()))
                                    <td class="px-3 py-2 whitespace-nowrap hidden sm:table-cell">
                                        <div class="flex items-center justify-end gap-1">
                                            @foreach ($this->actions() as $action)
                                                @if ($action['type'] === 'route')
                                                    <x-ui.link-button  wire:navigate
                                                        variant="{{ $action['variant'] }}"
                                                        href="{{ route($action['route'], $r->id) }}"
                                                        title="{{ $action['label'] }}"
                                                        class="{{ $action['class'] ?? '' }}">
                                                        {{ $action['content'] }}
                                                    </x-ui.link-button>
                                                @elseif ($action['type'] === 'method')
                                                    <x-ui.button type="button"
                                                        variant="{{ $action['variant'] }}"
                                                        wire:click="{{ $action['method'] }}({{ $r->id }})"
                                                        title="{{ $action['label'] }}"
                                                        class="{{ $action['class'] ?? '' }}">
                                                        {{ $action['content'] }}

                                                    </x-ui.button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                @endif
                            </tr>

                            {{-- Mobile details --}}
                            <tr x-show="open" x-cloak x-transition
                                class="sm:hidden {{ $loop->odd ? 'row-odd' : 'row-even' }}">
                                <td colspan="{{ count($columns) + (!empty($this->actions()) ? 2 : 1) }}"
                                    class="px-4 pb-3">
                                    <div class="rounded-xl border border-base-300/60 p-3 bg-base-200/40 space-y-3">
                                        <dl class="space-y-2">
                                            @foreach ($columns as $c)
                                                @php
                                                    $field = $c['field'];
                                                    $type = $c['type'] ?? 'text';
                                                    $val = data_get($r, $field);
                                                @endphp
                                                <div>
                                                    <dt
                                                        class="text-[11px] uppercase tracking-wide text-base-content/60">
                                                        {{ $c['label'] ?? ucfirst($field) }}
                                                    </dt>
                                                    <dd class="text-sm">
                                                        @if ($field === 'status')
                                                            @php $s = (string) $r->status; @endphp
                                                            <span
                                                                class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold
                                                                {{ $s === 'open'
                                                                    ? 'bg-info/10 text-info'
                                                                    : ($s === 'pending'
                                                                        ? 'bg-warning/10 text-warning'
                                                                        : ($s === 'approved'
                                                                            ? 'bg-success/10 text-success'
                                                                            : 'bg-error/10 text-error')) }}">
                                                                {{ ucfirst($s) }}
                                                            </span>
                                                        @elseif ($type === 'date' && !empty($c['format']) && $val)
                                                            {{ \Illuminate\Support\Carbon::parse($val)->format($c['format']) }}
                                                        @else
                                                            {{ $val }}
                                                        @endif
                                                    </dd>
                                                </div>
                                            @endforeach
                                        </dl>

                                        @if (!empty($this->actions()))
                                            <div class="flex flex-wrap gap-1 justify-start">
                                                @foreach ($this->actions() as $action)
                                                    @if ($action['type'] === 'route')
                                                        <x-ui.button  wire:navigate
                                                        variant="{{ $action['variant'] }}"
                                                        href="{{ route($action['route'], $r->id) }}"
                                                        title="{{ $action['label'] }}"
                                                        class="{{ $action['class'] ?? '' }}">
                                                        {{ $action['content'] }}
                                                        </x-ui.button>
                                                    @elseif ($action['type'] === 'method')
                                                        <x-ui.button type="button"
                                                        variant="{{ $action['variant'] }}"  
                                                        wire:click="{{ $action['method'] }}({{ $r->id }})"
                                                        title="{{ $action['label'] }}"
                                                        class="{{ $action['class'] ?? '' }}">
                                                        {{ $action['content'] }}

                                                        </x-ui.button>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td colspan="{{ count($columns) + (!empty($this->actions()) ? 2 : 1) }}">
                                    <div class="p-6 text-center text-base-content/60 text-sm">
                                        No results found. Try changing filters or search terms.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @endforelse
                </table>
            </div>

            {{-- Pagination --}}
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
