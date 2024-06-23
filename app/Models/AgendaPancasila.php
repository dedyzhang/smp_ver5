<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaPancasila extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'agenda_pancasila';
    protected $fillable = [
        'id_agenda',
        'id_guru',
        'tanggal',
        'id_siswa',
        'dimensi',
        'keterangan',
        'semester'
    ];

    public function siswa() {
        return $this->belongsTo(Siswa::class,'id_siswa');
    }
}
