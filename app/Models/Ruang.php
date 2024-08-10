<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruang extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'sapras_ruang';
    protected $fillable = [
        'kode',
        'nama',
        'warna',
        'umum'
    ];

    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_ruang', 'uuid');
    }
}
