<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    //
    protected $table = 'node';
    protected $fillable = ['code', 'name'];
    protected $guarded = [];
}
