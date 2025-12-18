<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataTableExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    WithChunkReading,
    ShouldAutoSize
{
    public function __construct(
        protected Builder $query,
        protected array $columns,
    ) {}

    public function query(): Builder
    {
        // Return the query as-is (with eager loading already applied)
        return $this->query;
    }
    
    public function headings(): array
    {
        return array_map(
            fn($c) => $c['label'] ?? ucfirst($c['field']),
            $this->columns
        );
    }

    public function map($row): array
    {
        $cells = [];

        foreach ($this->columns as $c) {
            $cells[] = $this->formatExportValue($row, $c);
        }

        return $cells;
    }

    /**
     * Process records in chunks to reduce memory usage
     */
    public function chunkSize(): int
    {
        return 1000; // Adjust based on your data complexity
    }

    /**
     * Format cell values for export
     */
    protected function formatExportValue($row, array $col): string
    {
        $field = $col['field'] ?? '';
        $type  = $col['type'] ?? 'text';
        $val   = data_get($row, $field);

        if ($type === 'boolean') {
            $on  = $col['options'][1] ?? 'Active';
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