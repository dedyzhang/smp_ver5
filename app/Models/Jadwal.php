<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'jadwal';
    protected $fillable = [
        'id_jadwal',
        'id_hari',
        'id_waktu',
        'jenis',
        'id_ngajar',
        'id_pelajaran',
        'id_guru',
        'id_kelas',
        'spesial'
    ];

    public function kelas() {
        return $this->belongsTo(Kelas::class,'id_kelas');
    }
    public function guru() {
        return $this->belongsTo(Guru::class,'id_guru');
    }
    public function ngajar() {
        return $this->belongsTo(Ngajar::class,'id_ngajar');
    }
    public function pelajaran() {
        return $this->belongsTo(Pelajaran::class,'id_pelajaran');
    }
    public function hari() {
        return $this->belongsTo(JadwalHari::class,'id_hari');
    }
    public function waktu() {
        return $this->belongsTo(JadwalWaktu::class,'id_waktu');
    }
}
