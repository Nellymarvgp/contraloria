<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NominaDetalle extends Model
{
    protected $fillable = [
        'nomina_id',
        'empleado_id',
        'sueldo_basico',
        'prima_profesionalizacion',
        'prima_antiguedad',
        'prima_por_hijo',
        'total',
    ];

    protected $casts = [
        'sueldo_basico' => 'decimal:2',
        'prima_profesionalizacion' => 'decimal:2',
        'prima_antiguedad' => 'decimal:2',
        'prima_por_hijo' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function nomina()
    {
        return $this->belongsTo(Nomina::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function conceptos()
    {
        return $this->hasMany(NominaDetalleConcepto::class);
    }
}
