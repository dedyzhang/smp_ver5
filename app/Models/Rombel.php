<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'rombels';
    protected $fillable = [
        'id_siswa',
        'id_kelas'
    ];

    public function siswa ()
    {
        return $this->belongsTo(Siswa::class,'id_siswa');
    }
    public function kelas ()
    {
        return $this->belongsTo(Kelas::class,'id_kelas');
    }
}
