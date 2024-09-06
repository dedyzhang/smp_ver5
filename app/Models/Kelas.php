<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'tingkat',
        'kelas'
    ];
    public function walikelas(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'walikelas', 'id_kelas', 'id_guru');
    }
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'uuid')->orderBy('nama', 'ASC');
    }
}
