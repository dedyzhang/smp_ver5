<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuangKelas extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'sapras_ruang_kelas';
    protected $fillable = [
        'id_kelas',
        'id_ruang'
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang');
    }
}
