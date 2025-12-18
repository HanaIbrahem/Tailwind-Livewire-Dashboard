<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class BlazingFastExport
{
    public function __construct(
        protected string $table,
        protected array $columns,
        protected array $wheres = [],
        protected ?string $orderBy = null,
        protected string $orderDir = 'asc'
    ) {}

    public function download(string $filename)
    {
        return response()->stream(function () {
            $writer = new Writer();
            $writer->openToFile('php://output');

            // Headers
            $headers = array_map(
                fn($c) => $c['label'] ?? ucfirst($c['field']),
                $this->columns
            );
            $writer->addRow(Row::fromValues($headers));

            // Get only needed columns for SELECT
            $selectFields = $this->buildSelectFields();
            
            // Use raw DB query (bypasses Eloquent overhead)
            $query = DB::table($this->table)->select($selectFields);
            
            // Apply where clauses
            foreach ($this->wheres as $where) {
                $query->where($where[0], $where[1], $where[2]);
            }
            
            // Apply ordering
            if ($this->orderBy) {
                $query->orderBy($this->orderBy, $this->orderDir);
            }
            
            // Stream in large chunks with cursor (most efficient)
            $query->orderBy('id')->chunk(10000, function ($rows) use ($writer) {
                $batch = [];
                
                foreach ($rows as $row) {
                    $cells = [];
                    foreach ($this->columns as $c) {
                        $field = $c['field'];
                        $cells[] = $this->formatValue($row->$field ?? '', $c);
                    }
                    $batch[] = Row::fromValues($cells);
                    
                    // Write in sub-batches of 1000 for memory efficiency
                    if (count($batch) >= 1000) {
                        $writer->addRows($batch);
                        $batch = [];
                    }
                }
                
                // Write remaining
                if (!empty($batch)) {
                    $writer->addRows($batch);
                }
            });

            $writer->close();
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    protected function buildSelectFields(): array
    {
        $fields = [];
        foreach ($this->columns as $c) {
            $field = $c['field'];
            // Skip relation fields for raw query
            if (strpos($field, '.') === false) {
                $fields[] = $field;
            }
        }
        return array_unique($fields);
    }

    protected function formatValue($value, array $col)
    {
        $type = $col['type'] ?? 'text';

        if ($type === 'boolean') {
            $on = $col['options'][1] ?? 'Active';
            $off = $col['options'][0] ?? 'Inactive';
            return $value ? $on : $off;
        }

        if ($type === 'date' && !empty($col['format']) && $value) {
            try {
                return \Carbon\Carbon::parse($value)->format($col['format']);
            } catch (\Throwable $e) {
                return $value;
            }
        }

        if ($type === 'number') {
            return (float) $value;
        }

        return $value ?? '';
    }
}