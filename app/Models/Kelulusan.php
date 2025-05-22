<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelulusan extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'kelulusan';
    protected $fillable = [
        'id_siswa',
        'nilai',
        'file',
        'kelulusan'
    ];
}
