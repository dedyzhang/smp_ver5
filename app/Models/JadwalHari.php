<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalHari extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'jadwal_hari';
    protected $fillable = [
        'no_hari',
        'nama_hari'
    ];
}
