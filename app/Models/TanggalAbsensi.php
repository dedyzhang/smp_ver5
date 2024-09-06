<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TanggalAbsensi extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'absensi_tanggal';
    protected $fillable = [
        'tanggal',
        'agenda',
        'ada_siswa',
        'semester',
        'id_jadwal'
    ];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalVer::class, 'id_jadwal');
    }
}
