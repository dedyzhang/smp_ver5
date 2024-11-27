<?php

namespace App\Exports;

use App\Models\Ngajar;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PenjabaranExport implements WithMultipleSheets
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

        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_kelas', $this->params)
            ->whereIn('pelajaran.has_penjabaran', array('1', '2'))
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->get();
        foreach ($ngajar as $item) {
            $sheets[] = new PenjabaranSheetExport($this->params, $item);
        }

        return $sheets;
    }
}
