@props([
    //modal open button text
    'button' => 'Delete',
    // modal message
    'message' => '',
    // modal header
    'header' => '',
])
@php
    // if we have more than one modal we need a uniqid 
    $modalid= $attributes->get('id')?? 'modal'.uniqid();
@endphp
<div>


    {{-- show button  --}}
    <x-ui.button  class="text-gray-200" onclick="{{ $modalid }}.showModal()">{{ $button }}</x-ui.button>
    
    {{-- modal content --}}
    <dialog id="{{ $modalid }}"   onclick="if (event.target === this) this.close()"  class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold">{{ $header }}</h3>
            <p class="py-4">{{ $message }}</p>

            {{-- specific button in there   --}}
            <div class=" flex justify-end">



                {{-- content on modla that we passed appear here --}}
                {{ $slot }}


            </div>
        </div>
    </dialog>

</div>
