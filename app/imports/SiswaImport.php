<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $rows) {
            return [
                'tanggal_lahir' => $rows['tanggal_lahir']
            ];
        }
    }
    public function headingRow(): int
    {
        return 4;
    }
}
