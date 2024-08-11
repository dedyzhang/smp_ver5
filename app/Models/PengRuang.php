<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengRuang extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'sapras_penggunaan';
    protected $fillable = [
        'tanggal',
        'id_ruang',
        'id_jadwal',
        'id_waktu',
        'id_guru',
        'id_kelas',
        'id_pelajaran'
    ];
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
    public function pelajaran()
    {
        return $this->belongsTo(Pelajaran::class, 'id_pelajaran');
    }
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang');
    }
    public function waktu()
    {
        return $this->belongsTo(JadwalWaktu::class, 'id_waktu');
    }
}
