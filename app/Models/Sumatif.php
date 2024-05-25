<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sumatif extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'nilai_sumatif';
    protected $fillable = [
        'id_materi',
        'id_siswa',
        'nilai'
    ];
}
