<?php

namespace App\Livewire\Table;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Locked;

class PostTable extends DataTable
{
   public string $title = 'Posts';

    public array $dateFields = [
        'created_at' => 'Uploaded at',
    ];
    public string $dateField = 'created_at';

    public ?string $dateFrom = null;
    public ?string $dateTo = null;


    #[Locked]
    public $sortbydate = false;
    #[Locked]
    public $allowactios = true;

    #[Locked]
    public $allowselection = true;
    #[Locked]
    public $actionpath="includes.tables.post.row";

    #[Locked]
    public $allowselectionpath="includes.tables.post.select";


    
    protected $queryString = [
        'q' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
        'dateField' => ['except' => 'created_at'],
        'dateFrom' => ['except' => null],
        'dateTo' => ['except' => null],
    ];



    public function mount(): void
    {
        // IMPORTANT: initialize base filters
        parent::mount();

        // Defaults: current month
        $this->dateFrom ??= Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo ??= Carbon::now()->toDateString();
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
   

    public function clearFilters(): void
    {
        parent::clearFilters(); // reset q + column filters

        $this->dateField = 'created_at';
        $this->dateFrom = Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo = Carbon::now()->toDateString();
    }

    protected function modelClass(): string
    {
        return Post::class;
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
                'field' => 'title',
                'label' => 'Title',
                'search_on' => 'title',
                'filter_on' => ['title'],
                'sortable' => false,
                'hide_sm' => false,
                'filter' => 'none',
            ],
            [
                'field' => 'content',
                'label' => 'content',
                'search_on' => ['content'],
                'filter_on' => ['content'],
                'sortable' => true,
                'word_limit'=>10,
                'hide_sm' => true,
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


    public function deleteall( $ids)
    {

        dd($ids);
        return $ids;
    }

    public function delete($id)
    {
        $model = $this->modelClass();


        $row = $model::findOrFail($id);
        $row->delete();
        $this->dispatch('toast', type: 'success', message: 'deleted successfully.');

    }
}
