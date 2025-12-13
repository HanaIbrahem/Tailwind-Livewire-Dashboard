@props([
    // Open button text
    'button'   => 'Open',
    // Drawer message
    'message'  => '',
    // Drawer header
    'header'   => '',
    // Open button size (for x-ui.button)
    'btnsize'  => 'sm',
    // Button variant
    'variant'  => 'primary',
    // Extra button classes
    'btnclass' => '',
])

@php
    // Unique ID so you can use the component multiple times
    $modalid = $attributes->get('id') ?? 'drawer-' . uniqid();
@endphp

<div class="m-1">
    {{-- Trigger button --}}
    <x-ui.button
        variant="{{ $variant }}"
        size="{{ $btnsize }}"
        class="{{ $btnclass }}"
        onclick="document.getElementById('{{ $modalid }}').showModal()"
    >
        {{ $button }}
    </x-ui.button>

    {{-- Drawer modal --}}
    <dialog
        id="{{ $modalid }}"
        class="modal"
        {{-- click on backdrop area (dialog itself) => close --}}
        onclick="if (event.target === this) this.close()"
    >
        {{-- DaisyUI puts flex on .modal, so we just push the box to the right --}}
        <div
            class="modal-box h-full w-full max-w-sm sm:max-w-md ml-auto mr-0 rounded-none sm:rounded-l-2xl
                   flex flex-col p-0 bg-base-100 border-l border-base-300/60 relative"
        >
            {{-- Close button --}}
            <form method="dialog">
                <button
                    type="submit"
                    class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3"
                    aria-label="Close"
                >
                    ✕
                </button>
            </form>

            {{-- Header + message --}}
            <div class="px-4 pt-4 pb-3 border-b border-base-300/60">
                @if ($header)
                    <h3 class="text-lg font-bold">
                        {{ $header }}
                    </h3>
                @endif

                @if ($message)
                    <p class="mt-1 text-sm text-base-content/70">
                        {{ $message }}
                    </p>
                @endif
            </div>

            {{-- Scrollable content area --}}
            <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3">
                {{ $slot }}
            </div>
        </div>

        {{-- DaisyUI backdrop: clicking it also closes --}}
        <form method="dialog" class="modal-backdrop">
            <button aria-label="Close"></button>
        </form>
    </dialog>
</div>
