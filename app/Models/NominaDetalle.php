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
        'comida',
        'otras_primas',
        'ret_ivss',
        'ret_pie',
        'ret_lph',
        'ret_fpj',
        'ordinaria',
        'incentivo',
        'feriado',
        'gastos_representacion',
        'total',
        'txt_1',
        'txt_2',
        'txt_3',
        'txt_4',
        'txt_5',
        'txt_6',
        'txt_7',
        'txt_8',
        'txt_9',
        'txt_10'
    ];

    protected $casts = [
        'sueldo_basico' => 'decimal:2',
        'prima_profesionalizacion' => 'decimal:2',
        'prima_antiguedad' => 'decimal:2',
        'prima_por_hijo' => 'decimal:2',
        'comida' => 'decimal:2',
        'otras_primas' => 'decimal:2',
        'ret_ivss' => 'decimal:2',
        'ret_pie' => 'decimal:2',
        'ret_lph' => 'decimal:2',
        'ret_fpj' => 'decimal:2',
        'ordinaria' => 'decimal:2',
        'incentivo' => 'decimal:2',
        'feriado' => 'decimal:2',
        'gastos_representacion' => 'decimal:2',
        'total' => 'decimal:2',
        'txt_1' => 'decimal:2',
        'txt_2' => 'decimal:2',
        'txt_3' => 'decimal:2',
        'txt_4' => 'decimal:2',
        'txt_5' => 'decimal:2',
        'txt_6' => 'decimal:2',
        'txt_7' => 'decimal:2',
        'txt_8' => 'decimal:2',
        'txt_9' => 'decimal:2',
        'txt_10' => 'decimal:2'
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
