<?php

namespace App\Exports;

use App\Models\data;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataExport  implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return data::all();
    }
    public function headings(): array
    {
        return [
            'Staff ID', 'Name', 'Date Time', 'Column 1', 'Column 2', 'Column 3'
        ];
    }
    public function map($item): array
    {
        $name = '';
        if ($item->staff_id == 1) {
            $name = 'shibin';
        } elseif ($item->staff_id == 2) {
            $name = 'Abdul Bari';
        } elseif ($item->staff_id == 3) {
            $name = 'fayiz';
        } elseif ($item->staff_id == 4) {
            $name = 'fasna';
        } elseif ($item->staff_id == 5) {
            $name = 'arashad';
        } else {
            $name = 'n/a';
        }

        return [
            $item->staff_id,
            $name,
            $item->date_time,
            $item->col_1,
            $item->col_2,
            $item->col_3,
        ];
    }
}
