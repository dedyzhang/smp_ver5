<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P3Poin extends Model
{
    use HasFactory, HasUuids;
    protected $table = "p3_poin";
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'tanggal',
        'id_siswa',
        'jenis',
        'deskripsi',
        'semester'
    ];

    public function siswa() {
        return $this->belongsTo(Siswa::class,'id_siswa');
    }
    public function kategori() {
        return $this->hasMany(P3Kategori::class,'jenis','jenis');
    }
}
