<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = "uuid";
    protected $table = "event";
    protected $fillable = [
        'judul',
        'id_ruang',
        'id_pengajuan',
        'waktu_mulai',
        'waktu_akhir',
        'deskripsi'
    ];

    public function guru() {
        return $this->belongsTo(Guru::class,'id_pengajuan');
    }
}
