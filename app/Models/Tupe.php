<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tupe extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'nilai_tujuan_pembelajaran';
    protected $fillable = [
        'id_materi',
        'tupe'
    ];
}
