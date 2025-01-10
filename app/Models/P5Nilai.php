<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P5Nilai extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'p5_nilai';
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'id_detail',
        'id_siswa',
        'nilai'
    ];
}
