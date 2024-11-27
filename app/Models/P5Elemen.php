<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P5Elemen extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'p5_elemen';
    protected $fillable = [
        'id_dimensi',
        'elemen'
    ];

    public function dimensi() {
        return $this->belongsTo(P5Dimensi::class,'id_dimensi');
    }

}
