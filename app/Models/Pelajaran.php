<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelajaran extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'pelajaran';
    protected $fillable = [
        'pelajaran',
        'pelajaran_singkat',
        'has_penjabaran',
        'urutan'
    ];
}
