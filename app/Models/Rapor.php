<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'nilai_rapor';
    protected $fillable = [
        'id_ngajar',
        'id_siswa',
        'nilai',
        'deskripsi_positif',
        'deskripsi_negatif',
        'semester'
    ];
}
