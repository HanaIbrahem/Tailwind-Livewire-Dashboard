<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Database\Eloquent\Model;
class UsersExport implements FromCollection
{

    public $model='';
    public function __construct(Model $model) {
        $this->model = $model;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->model::all();
    }
}
