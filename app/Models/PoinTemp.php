<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoinTemp extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'poin_temp';
    protected $fillable = [
        'tanggal',
        'id_aturan',
        'id_siswa',
        'penginput',
        'id_input',
        'status'
    ];
    public function aturan(): BelongsTo
    {
        return $this->belongsTo(Aturan::class, 'id_aturan');
    }
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
