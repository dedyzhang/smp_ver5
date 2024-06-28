<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'absensi_guru';
    protected $fillable = [
        'jenis',
        'id_tanggal',
        'id_guru',
        'waktu',
    ];

    public function tanggal() {
        return $this->belongsTo(TanggalAbsensi::class,'id_tanggal');
    }
}
