<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EkskulSiswa extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'ekskul_siswa';
    protected $fillable = [
        'id_ekskul',
        'id_siswa',
        'deskripsi',
        'semester'
    ];

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'id_ekskul');
    }
}
