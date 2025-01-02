<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P5Fasilitator extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'p5_fasilitator';
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'id_proyek',
        'id_guru',
        'id_kelas'
    ];

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class, 'id_proyek', 'uuid');
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'uuid');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'uuid');
    }
}
