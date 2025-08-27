<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P3Temp extends Model
{
    use hasUuids, HasFactory;
    protected $primaryKey = 'uuid';
    protected $table = 'p3_temp';
    protected $fillable = [
        'id_siswa',
        'yang_mengajukan',
        'id_pengajuan',
        'tanggal',
        'jenis',
        'deskripsi',
        'poin',
        'status',
        'semester'
    ];
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
