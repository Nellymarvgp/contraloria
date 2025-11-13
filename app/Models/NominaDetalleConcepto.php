<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NominaDetalleConcepto extends Model
{
    protected $fillable = [
        'nomina_detalle_id',
        'tipo', // asignacion, deduccion
        'descripcion',
        'monto',
        'porcentaje',
        'es_fijo' // true si es un monto fijo, false si es porcentaje
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'porcentaje' => 'decimal:2',
        'es_fijo' => 'boolean'
    ];

    public function nominaDetalle()
    {
        return $this->belongsTo(NominaDetalle::class);
    }
}
