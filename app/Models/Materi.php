<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'nilai_materi';
    protected $fillable = [
        'id_ngajar',
        'materi',
        'tupe',
        'semester',
        'show',
    ];

    public function Tupe()
    {
        return $this->hasMany(Tupe::class, 'id_materi', 'uuid');
    }
}
