<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabarKomputer extends Model
{
    use HasUuids, HasFactory;
    protected $primaryKey = 'uuid';
    protected $table = 'nilai_jabar_komputer';
    protected $fillable = [
        'id_ngajar',
        'id_siswa',
        'semester',
        'pengetahuan',
        'keterampilan'
    ];
}
