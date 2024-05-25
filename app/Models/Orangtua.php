<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orangtua extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'orangtua';
    protected $fillable = [
        'id_login',
        'id_siswa',
    ];

    public function siswa() {
        return $this->belongsTo(Siswa::class,'id_siswa');
    }
    public function users() {
        return $this->belongsTo(User::class,'id_login');
    }
}
