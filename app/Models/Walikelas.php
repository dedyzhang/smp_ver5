<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Walikelas extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = "uuid";
    protected $fillable = [
        'id_kelas',
        'id_guru'
    ];

    public function Kelas()
    {
        return $this->belongsTo(Kelas::class,'id_kelas');
    }
    public function Guru()
    {
        return $this->belongsTo(Guru::class,'id_guru');
    }
}
