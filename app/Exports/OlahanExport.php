<?php

namespace App\Exports;

use App\Models\Ngajar;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OlahanExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $params;

    function __construct($params)
    {
        $this->params = $params;
    }
    public function sheets(): array
    {
        $sheets = [];

        $ngajar = Ngajar::with('pelajaran')->where('id_kelas', $this->params)->get()->sortBy('pelajaran.urutan');

        foreach ($ngajar as $item) {
            $sheets[] = new OlahanSheetExport($this->params, $item);
        }

        return $sheets;
    }
}
