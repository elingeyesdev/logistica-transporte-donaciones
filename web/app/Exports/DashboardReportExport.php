<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DashboardReportExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected Collection $rows;
    protected array $headings;

    public function __construct(array $rows, array $headings)
    {
        $this->rows = collect($rows);
        $this->headings = $headings;
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
