<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoinTemp extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'poin_temp';
    protected $fillable = [
        'tanggal',
        'id_aturan',
        'id_siswa',
        'penginput',
        'id_input',
        'status'
    ];
}
