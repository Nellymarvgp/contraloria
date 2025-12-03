<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoVacaciones extends Model
{
    use HasFactory;

    protected $table = 'pagos_vacaciones';

    protected $fillable = [
        'empleado_id',
        'periodo',
        'monto',
        'year',
        'dias_pagados',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
