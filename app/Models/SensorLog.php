<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    protected $fillable = ['topic', 'value', 'gas', 'flame', 'buzzer'];
}
