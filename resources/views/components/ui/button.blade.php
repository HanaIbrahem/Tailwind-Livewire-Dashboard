@props([
    // button type like primary secondary ....
    'variant' => 'primary',
    'size'    => 'sm',   
    // button icon    
    'icon'    => null,       
])

@php

    // balse css class
    $base = 'btn';

    $variantClass = match ($variant) {
        'secondary' => 'btn-secondary',
        'ghost'     => 'btn-ghost',
        'outline'   => 'btn-outline',
        'success'   => 'btn-success',
        'error'     => 'btn-error',
        'warning'   => 'btn-warning',
        'info'      => 'btn-info',
        'link'      => 'btn-link',
        default     => 'btn-primary',
    };

    $sizeClass = match ($size) {
        'xs' => 'btn-xs',
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        'md' => 'btn-md',
      
    };

    $classes = trim("$base $variantClass $sizeClass");
@endphp

{{--two rypes of button href and button  --}}
@if ($attributes->has('href'))
    <a {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <x-dynamic-component :component="$icon" class="w-4 h-4" />
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $attributes->get('type', 'button') }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if ($icon)
            <x-dynamic-component :component="$icon" class="w-4 h-4" />
        @endif
        {{ $slot }}
    </button>
@endif
