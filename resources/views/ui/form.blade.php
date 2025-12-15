<div>


    <x-form.container title="Hello " description="i am a test form">

        <form method="post" class="grid grid-cols-1 md:grid-cols-12 gap-6" wire:submit="save(20)">

            <x-form.filed title="Name" class="md:col-span-6" :required="true" for="name">

                <x-form.input placeholder="Name">

                </x-form.input>


            </x-form.filed>

            <x-form.filed class="md:col-span-6" title="Email" :required="true" for="email">

                <x-form.input placeholder="Email">

                </x-form.input>


            </x-form.filed> 

         
            
            <div class="">
  <x-ui.button class="" type="submit" variant="info">
                Save
            </x-ui.button>
            </div>
          
        </form>
    </x-form.container>
</div>
