<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kokurikuler extends Model
{
    use HasUuids,HasFactory;
    protected $table = 'Kokurikuler';
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'id_siswa',
        'semester',
        'deskripsi'
    ];
}
