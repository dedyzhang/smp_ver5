<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory,HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'classroom';
    protected $fillable = [
        'id_bahan',
        'id_ngajar',
        'jenis',
        'judul',
        'tanggal_post',
        'tanggal_due',
        'deskripsi',
        'file',
        'link',
        'isi',
        'show_nilai',
        'status',
        'token'
    ];
}
