<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotulenRapat extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'notulen_rapat';
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'tanggal_rapat',
        'pokok_permasalahan',
        'hasil_rapat',
        'guru_hadir',
        'dokumentasi'
    ];
}
