<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruangan1 extends Model
{
    use HasFactory;

    // Nama tabel (jika tidak mengikuti konvensi laravel)
    protected $table = 'ruangan1s';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'name',
        'faculty',
        'building',
        'room',
    ];
}
