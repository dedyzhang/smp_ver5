<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P5ProyekDetail extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'p5_proyek_detail';
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'id_proyek',
        'id_dimensi',
        'id_elemen',
        'id_subelemen'
    ];

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class, 'id_proyek', 'uuid');
    }
    public function dimensi()
    {
        return $this->belongsTo(P5Dimensi::class, 'id_dimensi', 'uuid');
    }
    public function elemen()
    {
        return $this->belongsTo(P5Elemen::class, 'id_elemen', 'uuid');
    }
    public function subelemen()
    {
        return $this->belongsTo(P5Subelemen::class, 'id_subelemen', 'uuid');
    }
}
