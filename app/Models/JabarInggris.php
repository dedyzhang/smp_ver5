<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabarInggris extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'nilai_jabar_inggris';
    protected $fillable = [
        'id_ngajar',
        'id_siswa',
        'semester',
        'listening',
        'speaking',
        'writing',
        'reading',
        'grammar',
        'vocabulary',
        'singing'
    ];
}
