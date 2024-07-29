<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poin extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = "poin";
    protected $fillable = [
        'tanggal',
        'id_siswa',
        'id_aturan'
    ];

    public function aturan(): BelongsTo
    {
        return $this->belongsTo(Aturan::class, 'id_aturan');
    }
}
