<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'cedula',
        'cargo_id',
        'departamento_id',
        'horario_id',
        'estado_id',
        'salario',
        'fecha_ingreso',
        'observaciones'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'salario' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'cedula', 'cedula');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}
