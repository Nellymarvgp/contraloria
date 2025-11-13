<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoCargo extends Model
{
    protected $fillable = [
        'descripcion',
        'estado',
        'categoria'
    ];
}
