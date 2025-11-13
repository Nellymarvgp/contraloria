<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'despacho',
        'estado', // borrador, aprobada, pagada
        'total_monto'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'total_monto' => 'decimal:2'
    ];

    public function detalles()
    {
        return $this->hasMany(NominaDetalle::class);
    }
}
