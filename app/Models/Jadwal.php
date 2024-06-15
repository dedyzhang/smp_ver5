<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'jadwal';
    protected $fillable = [
        'id_jadwal',
        'id_hari',
        'id_waktu',
        'jenis',
        'id_ngajar',
        'id_pelajaran',
        'id_guru',
        'id_kelas'
    ];
}
