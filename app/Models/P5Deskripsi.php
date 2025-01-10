<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P5Deskripsi extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'p5_deskripsi';
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'id_proyek',
        'id_siswa',
        'deskripsi'
    ];
}
