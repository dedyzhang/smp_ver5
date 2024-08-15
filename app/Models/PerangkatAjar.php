<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerangkatAjar extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'perangkat_list';
    protected $fillable = [
        'perangkat'
    ];
}
