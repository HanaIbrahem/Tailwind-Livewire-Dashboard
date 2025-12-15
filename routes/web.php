<?php

use App\Livewire\Dashboard;
use App\Livewire\Ui\Form;
use App\Livewire\Ui\Alert;
use App\Livewire\Ui\Table;
use App\Livewire\Ui\Modal;
use App\Livewire\UserShow;
use Illuminate\Support\Facades\Route;


Route::get('/',Dashboard::class)->name('dashboard');
Route::get('/ui/table',Table::class)->name('table');
Route::get('/user/{user}',UserShow::class)->name('user.show');

Route::get('/ui/modal',Modal::class)->name('modal');
Route::get('/ui/alert',Alert::class)->name('alert');

Route::get('/ui/form',action: Form::class)->name('form');
