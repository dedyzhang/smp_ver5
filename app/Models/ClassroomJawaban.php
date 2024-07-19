<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomJawaban extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'classroom_jawaban';
    protected $fillable = [
        'id_classroom',
        'id_siswa',
        'jawaban',
        'nilai',
        'selesai',
        'status',
        'komentar'
    ];
}
