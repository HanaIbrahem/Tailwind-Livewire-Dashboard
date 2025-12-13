<div>



    <div class="flex flex-col bg-base-100 gap-3 ps-10 py-5">


        <div>
            <x-ui.button size="md" variant="error"
                wire:click="$dispatch('toast', { type: 'error', message: 'I am a error alert!' })">
                Error
            </x-ui.button>

        </div>

        <div>
            <x-ui.button size="md" variant="success"
                wire:click="$dispatch('toast', { type: 'success', message: 'I am a success alert!' })">
                Success
            </x-ui.button>
        </div>

        <div>
            <x-alert.alert class="text-white" message="i am error alert" type="error"/>
        </div>
    </div>




</div>
