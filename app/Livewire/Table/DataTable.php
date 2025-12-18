<?php
declare(strict_types=1);

namespace App\Livewire\Table;

use Livewire\Component;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Livewire\WithPagination;
use DB;
use Mpdf\Mpdf;
abstract class DataTable extends Component
{

    use WithPagination;

    protected $queryString = [
        'q' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public int $perPage = 10;
    public array $perPageOptions = [10, 50, 100];

    public string $title = '';
    public string $q = '';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';
    public array $filters = [];

    public $sortbydate = false;
    abstract protected function modelClass(): string;
    abstract protected function columns(): array;

    /** Hook: children can override to add base where / joins / scopes */
    protected function baseQuery(): Builder
    {
        $model = $this->modelClass();
        /** @var Builder $query */
        return $model::query();
    }

    // for actions like delete,edit others

    public function actions(): array
    {
        return [];
    }
    /** Hook: child can react to toggles (logging etc.) */
    protected function afterToggle(object $row, string $field): void
    {
        // default: no-op
    }

    protected $paginationTheme = 'tailwind';
    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
    public function paginationSimpleView()
    {
        return 'vendor.livewire.simple-tailwind';
    }


    public function mount(): void
    {
        // Initialize column filters once
        foreach ($this->columns() as $c) {
            $type = $c['filter'] ?? $this->defaultFilterForType($c['type'] ?? 'text');
            if ($type !== 'none') {
                $this->filters[$this->filterBindingKey($c)] = '';
            }
        }
    }

    public function updated($name): void
    {
        if (
            $name === 'q' || $name === 'perPage' ||
            str_starts_with($name, 'filters.') ||
            $name === 'sortField' || $name === 'sortDirection'
        ) {
            $this->resetPage();
        }
    }

    public function updatedPerPage($value): void
    {
        $v = (int) $value;
        if (!in_array($v, $this->perPageOptions, true)) {
            $v = 10;
        }
        $this->perPage = $v;
    }

    public function clearFilters(): void
    {
        $this->q = '';
        foreach ($this->columns() as $c) {
            $type = $c['filter'] ?? $this->defaultFilterForType($c['type'] ?? 'text');
            if ($type !== 'none') {
                $this->filters[$this->filterBindingKey($c)] = '';
            }

        }
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $cols = collect($this->columns())->keyBy('field');
        if (!$cols->has($field) || !($cols[$field]['sortable'] ?? true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    /** Generic toggle, plus hook for children */
    public function toggle(string $field, int $id): void
    {
        $model = $this->modelClass();
        $row = $model::query()->findOrFail($id);

        $row->{$field} = !(bool) $row->{$field};
        $row->save();

        $this->afterToggle($row, $field);

        session()->flash('ok', 'Updated.');
    }

    // ---------------- helpers ----------------

    protected function parseRelationField(string $field): ?array
    {
        if (strpos($field, '.') === false) {
            return null;
        }
        $parts = explode('.', $field);
        $column = array_pop($parts);
        $relation = implode('.', $parts);
        return [$relation, $column];
    }

    protected function filterBindingKey(array $c): string
    {
        return $c['filter_key'] ?? str_replace('.', '__', $c['field']);
    }

    protected function relationsFromColumns(array $cols): array
    {
        $with = [];
        foreach ($cols as $c) {
            $candidates = array_filter([
                $c['field'] ?? null,
                ...((array) ($c['filter_on'] ?? [])),
                ...((array) ($c['search_on'] ?? [])),
            ]);

            foreach ($candidates as $cand) {
                if ($rc = $this->parseRelationField($cand)) {
                    $with[] = $rc[0];
                }
            }
        }
        return array_values(array_unique($with));
    }

    protected function statusField(): ?string
    {
        foreach ($this->columns() as $c) {
            if (($c['type'] ?? 'text') === 'boolean' && ($c['status'] ?? false)) {
                return $c['field'];
            }
        }
        foreach ($this->columns() as $c) {
            if (($c['type'] ?? 'text') === 'boolean') {
                return $c['field'];
            }
        }
        return null;
    }

    public function toggleStatus(int $id): void
    {
        if ($field = $this->statusField()) {
            $this->toggle($field, $id);
        }
    }

    public function edit(int $id): void
    {
        $this->dispatch('edit', id: $id);
    }



    public function render()
    {
        $rows = $this->buildQuery()->paginate($this->perPage);

        return view('table.data-table', [
            'columns' => $this->columns(),
            'rows' => $rows,
            'title' => $this->title ?: class_basename($this->modelClass()),
        ]);
    }

    protected function tryOrderByBelongsTo(Builder $query, string $relationPath, string $column, string $dir): bool
    {
        $modelClass = $this->modelClass();
        $model = new $modelClass;

        if (str_contains($relationPath, '.')) {
            return false; // one hop only
        }
        if (!method_exists($model, $relationPath)) {
            return false;
        }

        $rel = $model->{$relationPath}();
        if (!$rel instanceof BelongsTo) {
            return false;
        }

        $related = $rel->getRelated();
        $relatedTable = $related->getTable();
        $target = "{$relatedTable}.{$column}";

        $sub = $related->newQuery()
            ->select($target)
            ->whereColumn($rel->getQualifiedOwnerKeyName(), $rel->getQualifiedForeignKeyName())
            ->limit(1);

        $query->orderBy($sub, $dir);
        return true;
    }

    /** MAIN: now uses baseQuery() so children can override only that */
    protected function buildQuery(): Builder
    {
        $query = $this->baseQuery();
        $cols = $this->columns();

        // 1) Eager-load relations
        $with = $this->relationsFromColumns($cols);
        if (!empty($with)) {
            $query->with($with);
        }

        // 2) Global search
        if ($this->q !== '') {
            $needle = trim($this->q);
            $lower = mb_strtolower($needle);
            $searchables = array_values(array_filter($cols, fn($c) => $c['searchable'] ?? true));

            $query->where(function (Builder $sub) use ($searchables, $needle, $lower) {
                foreach ($searchables as $c) {
                    $targets = (array) ($c['search_on'] ?? [$c['field']]);
                    foreach ($targets as $target) {
                        if ($rc = $this->parseRelationField($target)) {
                            [$relation, $column] = $rc;
                            $sub->orWhereHas($relation, function (Builder $q) use ($column, $needle) {
                                $q->where($column, 'like', "%{$needle}%");
                            });
                        } else {
                            $type = $c['type'] ?? 'text';
                            if ($type === 'text') {
                                $sub->orWhere($target, 'like', "%{$needle}%");
                            } elseif ($type === 'number' && is_numeric($needle)) {
                                $sub->orWhere($target, (int) $needle);
                            } elseif ($type === 'boolean') {
                                if (in_array($lower, ['1', 'true', 'yes', 'active', 'enabled', 'on', 'فعال'], true)) {
                                    $sub->orWhere($target, 1);
                                }
                                if (in_array($lower, ['0', 'false', 'no', 'inactive', 'disabled', 'off', 'غير فعال'], true)) {
                                    $sub->orWhere($target, 0);
                                }
                            } elseif ($type === 'date') {
                                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $needle)) {
                                    $sub->orWhereDate($target, $needle);
                                }
                            }
                        }
                    }
                }
            });
        }

        // 3) Column filters
        foreach ($cols as $c) {
            $type = $c['type'] ?? 'text';
            $filterT = $c['filter'] ?? $this->defaultFilterForType($type);

            $bindKey = $this->filterBindingKey($c);
            if (!array_key_exists($bindKey, $this->filters)) {
                continue;
            }

            $val = $this->filters[$bindKey];
            if ($val === '' || $val === null) {
                continue;
            }

            $filterOn = (array) ($c['filter_on'] ?? $c['field']);

            if ($filterT === 'text') {
                $query->where(function (Builder $w) use ($filterOn, $val) {
                    foreach ($filterOn as $target) {
                        if ($rc = $this->parseRelationField($target)) {
                            [$relation, $column] = $rc;
                            $w->orWhereHas($relation, function (Builder $q) use ($column, $val) {
                                $q->where($column, 'like', "%{$val}%");
                            });
                        } else {
                            $w->orWhere($target, 'like', "%{$val}%");
                        }
                    }
                });
            } elseif ($filterT === 'select') {
                foreach ($filterOn as $target) {
                    if ($rc = $this->parseRelationField($target)) {
                        [$relation, $column] = $rc;
                        $query->whereHas($relation, fn(Builder $q) => $q->where($column, $val));
                    } else {
                        $query->where($target, $val);
                    }
                }
            } elseif ($filterT === 'boolean') {
                $bool = (bool) intval($val);
                foreach ($filterOn as $target) {
                    if ($rc = $this->parseRelationField($target)) {
                        [$relation, $column] = $rc;
                        $query->whereHas($relation, fn(Builder $q) => $q->where($column, $bool));
                    } else {
                        $query->where($target, $bool);
                    }
                }
            } elseif ($filterT === 'date_range') {
                $field = $c['field'];
                $from = Arr::get($val, 'from');
                $to = Arr::get($val, 'to');
                if ($from) {
                    $query->whereDate($field, '>=', $from);
                }
                if ($to) {
                    $query->whereDate($field, '<=', $to);
                }
            }
        }

        // 4) Sorting
        $sortable = array_column(array_filter($cols, fn($c) => $c['sortable'] ?? true), 'field');

        if (in_array($this->sortField, $sortable, true)) {
            if ($rc = $this->parseRelationField($this->sortField)) {
                [$relation, $column] = $rc;
                if (!$this->tryOrderByBelongsTo($query, $relation, $column, $this->sortDirection)) {
                    $query->latest();
                }
            } else {
                $query->orderBy($this->sortField, $this->sortDirection);
            }
        } else {
            $query->latest();
        }

        return $query;
    }

    protected function defaultFilterForType(string $type): string
    {
        return match ($type) {
            'boolean' => 'boolean',
            'date' => 'none',
            default => 'text',
        };
    }


    
    
    public function export(string $format = 'xlsx')
{
    set_time_limit(0);
    ini_set('memory_limit', '512M');

    $format = strtolower($format);
    $basename = strtolower(class_basename($this->modelClass()));
    $filename = $basename . '-' . now()->format('Ymd_His');

    if ($format === 'xlsx') {
        return $this->exportXlsxRaw($filename);
    }

    if ($format === 'csv') {
        return $this->exportCsvRaw($filename);
    }

    return back()->with('error', 'Invalid format');
}

/**
 * Raw DB query export (bypasses Eloquent - 10x faster)
 */
protected function exportXlsxRaw(string $filename)
{
    $columns = $this->columns();
    
    return response()->stream(function () use ($columns) {
        $writer = new \OpenSpout\Writer\XLSX\Writer();
        $writer->openToFile('php://output');

        // Headers
        $headers = array_map(fn($c) => $c['label'] ?? ucfirst($c['field']), $columns);
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues($headers));

        // Get table name
        $model = $this->modelClass();
        $table = (new $model)->getTable();
        
        // Build raw SQL query
        $query = DB::table($table);
        
        // Apply filters from buildQuery logic
        $this->applyRawFilters($query);
        
        // Get only needed columns
        $selectFields = $this->getRawSelectFields($columns);
        $query->select($selectFields);
        
        // Stream data - use chunkById for better performance
        $query->orderBy('id')->chunkById(10000, function ($rows) use ($writer, $columns) {
            $batch = [];
            
            foreach ($rows as $row) {
                $cells = [];
                foreach ($columns as $c) {
                    $field = $c['field'];
                    $val = $row->$field ?? '';
                    $cells[] = $this->quickFormat($val, $c['type'] ?? 'text');
                }
                $batch[] = \OpenSpout\Common\Entity\Row::fromValues($cells);
                
                if (count($batch) >= 1000) {
                    $writer->addRows($batch);
                    $batch = [];
                }
            }
            
            if (!empty($batch)) {
                $writer->addRows($batch);
            }
        }, 'id');

        $writer->close();
    }, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => "attachment; filename=\"{$filename}.xlsx\"",
        'Cache-Control' => 'no-cache',
        'X-Accel-Buffering' => 'no',
    ]);
}

/**
 * Ultra-fast CSV export (fastest possible)
 */
protected function exportCsvRaw(string $filename)
{
    $columns = $this->columns();
    
    return response()->stream(function () use ($columns) {
        $out = fopen('php://output', 'w');
        
        // UTF-8 BOM for Excel compatibility
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        $headers = array_map(fn($c) => $c['label'] ?? ucfirst($c['field']), $columns);
        fputcsv($out, $headers);
        
        // Get table
        $model = $this->modelClass();
        $table = (new $model)->getTable();
        
        // Raw query
        $query = DB::table($table);
        $this->applyRawFilters($query);
        
        $selectFields = $this->getRawSelectFields($columns);
        $query->select($selectFields);
        
        // Stream with chunkById
        $query->orderBy('id')->chunkById(10000, function ($rows) use ($out, $columns) {
            foreach ($rows as $row) {
                $cells = [];
                foreach ($columns as $c) {
                    $field = $c['field'];
                    $val = $row->$field ?? '';
                    $cells[] = $this->quickFormat($val, $c['type'] ?? 'text');
                }
                fputcsv($out, $cells);
            }
        }, 'id');
        
        fclose($out);
    }, 200, [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        'Cache-Control' => 'no-cache',
    ]);
}

/**
 * Apply filters to raw query
 */
protected function applyRawFilters($query)
{
    // Global search
    if ($this->q !== '') {
        $needle = trim($this->q);
        $searchables = array_filter($this->columns(), fn($c) => $c['searchable'] ?? true);
        
        $query->where(function($q) use ($searchables, $needle) {
            foreach ($searchables as $c) {
                $field = $c['field'];
                if (strpos($field, '.') === false) { // Skip relations
                    $q->orWhere($field, 'like', "%{$needle}%");
                }
            }
        });
    }
    
    // Column filters
    foreach ($this->columns() as $c) {
        $bindKey = $this->filterBindingKey($c);
        if (!isset($this->filters[$bindKey])) continue;
        
        $val = $this->filters[$bindKey];
        if ($val === '' || $val === null) continue;
        
        $field = $c['field'];
        if (strpos($field, '.') !== false) continue; // Skip relations
        
        $filterType = $c['filter'] ?? 'text';
        
        if ($filterType === 'text') {
            $query->where($field, 'like', "%{$val}%");
        } elseif ($filterType === 'select') {
            $query->where($field, $val);
        } elseif ($filterType === 'boolean') {
            $query->where($field, (bool) intval($val));
        }
    }
    
    // Sorting
    if ($this->sortField && strpos($this->sortField, '.') === false) {
        $query->orderBy($this->sortField, $this->sortDirection);
    }
}

/**
 * Get raw select fields (no relations)
 */
protected function getRawSelectFields(array $columns): array
{
    $fields = ['id'];
    foreach ($columns as $c) {
        $field = $c['field'] ?? '';
        if (strpos($field, '.') === false) {
            $fields[] = $field;
        }
    }
    return array_unique($fields);
}

/**
 * Quick formatting (no Carbon parsing overhead)
 */
protected function quickFormat($value, string $type)
{
    if ($type === 'boolean') {
        return $value ? 'Active' : 'Inactive';
    }
    
    if ($type === 'number') {
        return (float) $value;
    }
    
    return $value ?? '';
}
/**
 * Build query optimized for export (less eager loading)
 */
protected function buildOptimizedExportQuery(): Builder
{
    $query = $this->baseQuery();
    $cols = $this->columns();

    // Only load relations that are actually used in export
    $exportRelations = $this->getExportRelations($cols);
    if (!empty($exportRelations)) {
        $query->with($exportRelations);
    }

    // Apply filters (same as buildQuery)
    // ... copy filter logic from buildQuery() ...
    
    // Global search
    if ($this->q !== '') {
        $needle = trim($this->q);
        $lower = mb_strtolower($needle);
        $searchables = array_values(array_filter($cols, fn($c) => $c['searchable'] ?? true));

        $query->where(function (Builder $sub) use ($searchables, $needle, $lower) {
            foreach ($searchables as $c) {
                $targets = (array) ($c['search_on'] ?? [$c['field']]);
                foreach ($targets as $target) {
                    if ($rc = $this->parseRelationField($target)) {
                        [$relation, $column] = $rc;
                        $sub->orWhereHas($relation, function (Builder $q) use ($column, $needle) {
                            $q->where($column, 'like', "%{$needle}%");
                        });
                    } else {
                        $sub->orWhere($target, 'like', "%{$needle}%");
                    }
                }
            }
        });
    }

    // Column filters
    foreach ($cols as $c) {
        $type = $c['type'] ?? 'text';
        $filterT = $c['filter'] ?? $this->defaultFilterForType($type);
        $bindKey = $this->filterBindingKey($c);
        
        if (!array_key_exists($bindKey, $this->filters)) {
            continue;
        }

        $val = $this->filters[$bindKey];
        if ($val === '' || $val === null) {
            continue;
        }

        $filterOn = (array) ($c['filter_on'] ?? $c['field']);

        if ($filterT === 'text') {
            $query->where(function (Builder $w) use ($filterOn, $val) {
                foreach ($filterOn as $target) {
                    $w->orWhere($target, 'like', "%{$val}%");
                }
            });
        } elseif ($filterT === 'select') {
            foreach ($filterOn as $target) {
                $query->where($target, $val);
            }
        }
    }

    // Sorting
    $sortable = array_column(array_filter($cols, fn($c) => $c['sortable'] ?? true), 'field');
    if (in_array($this->sortField, $sortable, true)) {
        $query->orderBy($this->sortField, $this->sortDirection);
    } else {
        $query->latest();
    }

    return $query;
}

/**
 * Get only relations needed for export columns
 */
protected function getExportRelations(array $cols): array
{
    $with = [];
    foreach ($cols as $c) {
        $field = $c['field'] ?? '';
        if ($rc = $this->parseRelationField($field)) {
            $with[] = $rc[0];
        }
    }
    return array_values(array_unique($with));
}

/**
 * Get field list for SELECT optimization
 */
protected function getExportFields(array $cols): array
{
    $fields = ['id']; // Always include ID
    
    foreach ($cols as $c) {
        $field = $c['field'] ?? '';
        if (strpos($field, '.') === false) {
            $fields[] = $field;
        }
    }
    
    return array_unique($fields);
}


    protected function formatExportValue($row, array $col): string
    {
        $field = $col['field'] ?? '';
        $type = $col['type'] ?? 'text';
        $val = data_get($row, $field);

        if ($type === 'boolean') {
            $on = $col['options'][1] ?? 'Active';
            $off = $col['options'][0] ?? 'Inactive';
            return (string) ((bool) $val ? $on : $off);
        }

        if ($type === 'date' && !empty($col['format']) && $val) {
            try {
                return \Illuminate\Support\Carbon::parse($val)->format($col['format']);
            } catch (\Throwable $e) {
                return (string) $val;
            }
        }

        return is_scalar($val) || $val === null
            ? (string) $val
            : json_encode($val, JSON_UNESCAPED_UNICODE);
    }
}

