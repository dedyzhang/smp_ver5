<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'sapras_barang';
    protected $fillable = [
        'id_ruang',
        'barang',
        'merk',
        'penyedia',
        'tanggal',
        'deskripsi',
        'jumlah'
    ];
    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class, 'id_ruang');
    }
}
