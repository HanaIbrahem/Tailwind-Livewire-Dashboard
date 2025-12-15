<?php

namespace App\Livewire\Ui;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Form extends Component
{
    #[Validate('required|min:3|max:20')]
    public $name='';
      
    #[Validate('required|email')]
    public $email='';
    public function render()
    {
        return view('ui.form');
    }

    public function save(User $user)
    {
        
        $validate=$this->validate();

        $user->update([
            $validate
        ]);

        $this->dispatch('toast',type:'success',message:'user name updated');
    }
}
