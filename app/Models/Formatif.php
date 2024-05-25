<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formatif extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'nilai_formatif';
    protected $fillable = [
        'id_materi',
        'id_tupe',
        'id_siswa',
        'nilai'
    ];
}
