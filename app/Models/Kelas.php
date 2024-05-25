<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kelas extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'tingkat',
        'kelas'
    ];
    public function walikelas() : BelongsToMany {
        return $this->belongsToMany(Guru::class,'walikelas','id_kelas','id_guru');
    }
}
