<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimaProfesionalizacion extends Model
{
    protected $fillable = [
        'descripcion',
        'porcentaje',
        'estado'
    ];
}
