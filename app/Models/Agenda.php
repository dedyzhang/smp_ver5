<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'agenda';
    protected $fillable = [
        'tanggal',
        'id_versi',
        'id_jadwal',
        'id_guru',
        'pembahasan',
        'metode',
        'proses',
        'kegiatan',
        'kendala',
        'validasi',
        'catatan_kepsek',
        'semester'
    ];
    public function absensi() {
        return $this->hasMany(AgendaAbsensi::class,'id_agenda','uuid');
    }
    public function pancasila() {
        return $this->hasMany(AgendaPancasila::class,'id_agenda','uuid');
    }
    public function jadwal() {
        return $this->belongsTo(Jadwal::class,'id_jadwal');
    }
}
