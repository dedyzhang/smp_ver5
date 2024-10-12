<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaporManual extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'rapor_manual';
    protected $fillable = [
        'id_ngajar',
        'id_siswa',
        'nilai',
        'deskripsi_positif',
        'deskripsi_negatif',
        'semester'
    ];
}
