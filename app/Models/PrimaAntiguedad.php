<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimaAntiguedad extends Model
{
    protected $fillable = [
        'anios',
        'porcentaje',
        'estado'
    ];
}
