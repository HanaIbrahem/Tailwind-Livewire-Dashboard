{{-- <x-modal.modal header="Delete!" message="Are you shure delete it? this action cant be returnded" variant="error"
    btnsize="xs" button="Delete">
    <x-slot:buttonslot>
        <x-ui.button wire:click="delete({{ $r->id }})" size="xs" variant="error">
            Delete
        </x-ui.button>
    </x-slot:buttonslot>
</x-modal.modal> --}}

  <x-ui.button wire:click="delete({{ $r->id }})" size="xs" variant="error">
            Delete
        </x-ui.button>