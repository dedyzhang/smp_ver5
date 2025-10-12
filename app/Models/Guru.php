<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Guru extends Model
{
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'uuid';
    protected $fillable = [
        'id_login',
        'nama',
        'nik',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'tingkat_studi',
        'program_studi',
        'universitas',
        'tahun_tamat',
        'tmt_ngajar',
        'tmt_smp',
        'no_telp',
        'sekretaris'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_login');
    }
    public function walikelas()
    {
        return $this->belongsTo(Walikelas::class, 'uuid', 'id_guru');
    }
}
