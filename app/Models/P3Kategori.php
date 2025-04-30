<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P3Kategori extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'p3_kategori';
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'jenis',
        'deskripsi',
    ];
}
