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
        'tiempo_antiguedad',
        'observaciones',
        'prima_antiguedad_id',
        'prima_profesionalizacion_id',
        'nivel_rango_id',
        'grupo_cargo_id',
        'tipo_cargo',
        'tiene_hijos',
        'cantidad_hijos',
        'pvacaciones',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'salario' => 'decimal:2',
        'pvacaciones' => 'boolean',
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

    public function primaAntiguedad()
    {
        return $this->belongsTo(PrimaAntiguedad::class);
    }

    public function primaProfesionalizacion()
    {
        return $this->belongsTo(PrimaProfesionalizacion::class);
    }

    public function nivelRango()
    {
        return $this->belongsTo(NivelRango::class);
    }

    public function grupoCargo()
    {
        return $this->belongsTo(GrupoCargo::class);
    }

    /**
     * Beneficios asociados al empleado (tabla beneficios + pivote beneficio_empleado).
     */
    public function beneficios()
    {
        return $this->belongsToMany(\App\Models\Beneficio::class, 'beneficio_empleado', 'empleado_id', 'beneficio_id')
            ->withPivot('valor_extra');
    }

    /**
     * Deducciones personalizadas asociadas al empleado.
     */
    public function deducciones()
    {
        return $this->belongsToMany(\App\Models\Deduccion::class, 'empleado_deduccion', 'empleado_id', 'deduccion_id')->withPivot('valor_extra')->where('tipo', 'deduccion');
    }

    /**
     * Solicitudes de vacaciones del empleado.
     */
    public function vacaciones()
    {
        return $this->hasMany(Vacacion::class);
    }

    /**
     * Registros de días por disfrute asignados al empleado.
     */
    public function vacacionesPorDisfrute()
    {
        return $this->hasMany(VacacionesPorDisfrute::class);
    }
}
