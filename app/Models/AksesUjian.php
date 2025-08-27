<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesUjian extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'akses_ujian';
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'id_guru',
        'semester',
    ];
}
