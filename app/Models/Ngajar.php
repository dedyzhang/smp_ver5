<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ngajar extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'ngajar';
    protected $fillable = [
        'id_guru',
        'id_pelajaran',
        'id_kelas',
        'kkm'
    ];

    public function guru() {
        return $this->belongsTo(Guru::class,'id_guru');
    }
    public function pelajaran() {
        return $this->belongsTo(Pelajaran::class,'id_pelajaran');
    }
    public function kelas() {
        return $this->belongsTo(Kelas::class,'id_kelas');
    }
    public function siswa() {
        return $this->belongsToMany(Siswa::class,'kelas','uuid','uuid','id_kelas','id_kelas');
    }
}
