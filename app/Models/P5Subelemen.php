<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P5Subelemen extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'p5_subelemen';
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'id_elemen',
        'subelemen',
        'capaian'
    ];

    public function elemen()
    {
        return $this->belongsTo(P5Elemen::class, 'id_elemen');
    }
}
