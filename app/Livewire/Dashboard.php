<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('index page')]
class Dashboard extends Component
{
    public function render()
    {
        return view('dashboard');
    }
}
