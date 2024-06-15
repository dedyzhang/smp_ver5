<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalWaktu extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'jadwal_waktu';
    protected $fillable = [
        'id_jadwal',
        'waktu_mulai',
        'waktu_akhir'
    ];
}
