@props([
    'type' => 'text',
    'placeholder' => '',
])

<input type="{{ $type }}"
    class="input input-bordered w-full
    focus:border-primary focus:ring focus:ring-primary/20 focus:outline-none
    @error('{{ $for }}') input-error border-error @enderror"
    aria-invalid="@error('{{ $for }}}}') true @else false @enderror" 
    placeholder="{{ $placeholder }}"
/>
