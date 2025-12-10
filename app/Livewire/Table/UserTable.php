<?php

namespace App\Livewire\Table;

use App\Models\User;
use Carbon\Carbon;
class UserTable extends DataTable
{
    public string $title = 'Users';

    public $sortbydate = false;
    public array $dateFields = [
        'created_at' => 'Uploaded at',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'approved_at' => 'Approved at',
    ];
    public string $dateField = 'created_at';

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $dateApproved = null;

    protected $queryString = [
        'q' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
        'dateField' => ['except' => 'created_at'],
        'dateFrom' => ['except' => null],
        'dateTo' => ['except' => null],
        'dateApproved' => ['except' => null],
    ];



    public function mount(): void
    {
        // IMPORTANT: initialize base filters
        parent::mount();

        // Defaults: current month
        $this->dateFrom ??= Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo ??= Carbon::now()->toDateString();
        $this->dateApproved ??= Carbon::now()->toDateString();
    }

    public function updatedDateField(): void
    {
        $this->resetPage();
    }
    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }
    public function updatedDateTo(): void
    {
        $this->resetPage();
    }
    public function updatedDateApproved(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        parent::clearFilters(); // reset q + column filters

        $this->dateField = 'created_at';
        $this->dateFrom = Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo = Carbon::now()->toDateString();
        $this->dateApproved = Carbon::now()->toDateString();
    }

    protected function modelClass(): string
    {
        return User::class;
    }


    protected function columns(): array
    {
        return [
            [
                'field' => 'id',
                'label' => 'ID',
                'width' => 'w-25',
                'search_on' => 'id',
                'filter_on' => 'id',
                'sortable' => true,
                'hide_sm' => false,
                'filter' => 'text',
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'search_on' => ['email'],
                'filter_on' => ['email'],
                'sortable' => false,
                'hide_sm' => false,
                'filter' => 'text',
            ],
            [
                'field' => 'name',
                'label' => 'Employee',
                'search_on' => ['name'],
                'filter_on' => ['name'],
                'sortable' => false,
                'hide_sm' => false,
                'filter' => 'text',
            ],
            [
                'field' => 'created_at',
                'label' => 'created',
                'type' => 'date',
                'format' => 'Y-m-d',
                'sortable' => true,
                'hide_sm' => false,
            ],
        ];
    }


    public function actions(): array
    {
        return [
            // edit action
            [
                'type' => 'route',
                'label' => 'Edit',
                'content' => 'show',
                'route' => 'user.show',    // route name
                'param' => 'id',
                'class' => 'btn btn-danger btn-xs hover:text-gray-200'

            ],
            // Delete -> Livewire function
            [
                'type' => 'method',
                'label' => 'Delete',
                'content' => 'Delete',
                'class' => 'btn btn-danger btn-xs hover:text-gray-700',
                'method' => 'delete', // Livewire method on this component
                'param' => 'id',              // row field to pass as argument
            ]
        ];
    }

    public function delete($id)
    {
        $model = $this->modelClass();


        $row = $model::findOrFail($id);
        $row->delete();
        $this->dispatch('toast', type: 'success', message: 'deleted successfully.');

        $this->resetPage();

    }
}
