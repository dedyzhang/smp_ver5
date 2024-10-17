<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'ekskul';
    protected $fillable = [
        'ekskul',
        'id_guru',
        'id_pelajaran',
        'urutan'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }
    public function pelajaran()
    {
        return $this->belongsTo(Pelajaran::class, 'id_pelajaran');
    }
}
