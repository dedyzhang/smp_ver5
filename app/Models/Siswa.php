<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Siswa extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'siswa';
    protected $fillable = [
        'id_login',
        'nama',
        'nis',
        'id_kelas',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'no_handphone',
        'nama_ayah',
        'pekerjaan_ayah',
        'no_telp_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'no_telp_ibu',
        'nama_wali',
        'pekerjaan_wali',
        'no_telp_wali',
        'nisn',
        'sekolah_asal',
        'nama_ijazah',
        'ortu_ijazah',
        'tempat_lahir_ijazah',
        'tanggal_lahir_ijazah',
        'va',
        'spp'
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'id_login');
    }
    public function orangtua(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'orangtua', 'id_siswa', 'id_login');
    }
    public function ortu(): BelongsTo
    {
        return $this->belongsTo(Orangtua::class, 'uuid', 'id_siswa');
    }
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
