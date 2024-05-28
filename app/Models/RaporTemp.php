<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaporTemp extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'nilai_rapor_temp';
    protected $fillable = [
        'id_ngajar',
        'id_siswa',
        'jenis',
        'perubahan'
    ];
}
