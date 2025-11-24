<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimaHijo extends Model
{
    protected $table = 'prima_hijos';

    protected $fillable = [
        'hijos',
        'porcentaje',
        'estado',
    ];
}
