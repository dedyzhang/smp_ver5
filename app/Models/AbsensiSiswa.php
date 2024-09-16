<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiSiswa extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'absensi_siswa';
    protected $fillable = [
        'id_tanggal',
        'id_siswa',
        'waktu',
        'absensi',
        'keterangan'
    ];

    public function tanggal(): BelongsTo
    {
        return $this->belongsTo(TanggalAbsensi::class, 'id_tanggal');
    }
}
