@props([
    //modal open button text
    'button' => 'Delete',
    // modal message
    'message' => '',
    // modal header
    'header' => '',
    // open button size
    'btnsize'=>'sm',
    // button type
    'variant'=>'primary',
    // button class
    'btnclass'=>''
])
@php
    // if we have more than one modal we need a uniqid 
    $modalid= $attributes->get('id')?? 'modal'.uniqid();
@endphp
<div class="m-1">


    {{-- show button  --}}
    <x-ui.button  variant="{{ $variant }}" size="{{$btnsize }}" class="{{ $btnclass }}" onclick="{{ $modalid }}.showModal()">{{ $button }}</x-ui.button>
    
    {{-- modal content --}}
    <dialog id="{{ $modalid }}"   onclick="if (event.target === this) this.close()"  class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold">{{ $header }}</h3>
            <p class="py-4">{{ $message }}</p>

            <div>
                {{ $slot }}
            </div>
            {{-- specific button in there   --}}
            <div class=" flex justify-end">


                {{-- content on modla that we passed appear here --}}
                @if(isset($buttonslot))
                    

                {{ $buttonslot }}
                @endif


            </div>
        </div>
    </dialog>

</div>
