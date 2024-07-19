<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class GuruExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {

        return view('cetak.guru.excel', ['guru' => Guru::orderBy('nama')->get()]);
    }
}
