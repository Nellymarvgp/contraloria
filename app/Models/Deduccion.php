<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduccion extends Model
{
    use HasFactory;

    protected $table = 'deducciones';
    protected $fillable = ['nombre', 'descripcion', 'porcentaje', 'es_fijo', 'monto_fijo', 'activo'];

    protected $casts = [
        'porcentaje' => 'float',
        'monto_fijo' => 'float',
        'es_fijo' => 'boolean',
        'activo' => 'boolean',
    ];
}
