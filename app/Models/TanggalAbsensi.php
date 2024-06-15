<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggalAbsensi extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'absensi_tanggal';
    protected $fillable = [
        'tanggal',
        'minggu_ke',
        'agenda',
        'ada_siswa',
        'semester'
    ];
}
