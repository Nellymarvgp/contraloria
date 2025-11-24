<?php

namespace App\Services;

use App\Models\Empleado;
use App\Models\Nomina;
use App\Models\Remuneracion;
use App\Models\PrimaAntiguedad;
use App\Models\PrimaProfesionalizacion;
use App\Models\PrimaHijo;
use App\Models\Deduccion;
use App\Models\BeneficioCargo;
use Carbon\Carbon;

class NominaCalculator
{
    /**
     * Calculate payroll for an employee.
     *
     * @param Empleado $empleado
     * @return array
     */
    public function calculate(Empleado $empleado, Nomina $nomina)
    {
        // Base calculations
        // Primero calcular el sueldo "quincenal" (mitad) como hasta ahora
        $sueldoBasicoQuincenal = $this->calcularSueldoBasico($empleado);

        // Calcular días del período de la nómina
        $diasPeriodo = null;
        if ($nomina->fecha_inicio && $nomina->fecha_fin) {
            $diasPeriodo = $nomina->fecha_inicio->diffInDays($nomina->fecha_fin) + 1;
        }

        // Regla solicitada:
        // - Si el cálculo tiene más de 25 días, se toma el sueldo completo (doble de la base quincenal).
        // - Si el cálculo tiene 15 días (o en general <= 25), se toma solo la mitad del sueldo base.
        if ($diasPeriodo !== null && $diasPeriodo > 25) {
            $sueldoBasico = $sueldoBasicoQuincenal * 2;
        } else {
            $sueldoBasico = $sueldoBasicoQuincenal;
        }

        $primaProfesionalizacion = $this->calcularPrimaProfesionalizacion($empleado, $sueldoBasico);
        $primaAntiguedad = $this->calcularPrimaAntiguedad($empleado, $sueldoBasico);
        $primaPorHijo = $this->calcularPrimaPorHijo($empleado, $sueldoBasico);
        $comida = $this->calcularComida($nomina);
        $otrasPrimas = $this->calcularOtrasPrimas($empleado);
        
        // Retenciones (deductions) - solo si el empleado tiene asignadas esas deducciones
        $retIvss = $this->calcularRetIVSS($empleado, $sueldoBasico);
        $retPie = $this->calcularRetPIE($empleado, $sueldoBasico);
        $retLph = $this->calcularRetLPH($empleado, $sueldoBasico);
        $retFpj = $this->calcularRetFPJ($empleado, $sueldoBasico);
        
        // Asignaciones adicionales (ahora manejadas como beneficios generales)
        $ordinaria = $this->calcularOrdinaria($sueldoBasico);
        $incentivo = $this->calcularIncentivo($empleado);
        $feriado = $this->calcularFeriado();
        $gastosRepresentacion = $this->calcularGastosRepresentacion($empleado);
        
        // Calculate total
        $total = $this->calcularTotal(
            $sueldoBasico,
            $primaProfesionalizacion,
            $primaAntiguedad,
            $primaPorHijo,
            $comida,
            $otrasPrimas,
            $retIvss,
            $retPie,
            $retLph,
            $retFpj,
            $ordinaria,
            $incentivo,
            $feriado,
            $gastosRepresentacion
        );
        
        // Return calculated values
        return [
            'sueldo_basico' => $sueldoBasico,
            'prima_profesionalizacion' => $primaProfesionalizacion,
            'prima_antiguedad' => $primaAntiguedad,
            'prima_por_hijo' => $primaPorHijo,
            'comida' => $comida,
            'otras_primas' => $otrasPrimas,
            'ret_ivss' => $retIvss,
            'ret_pie' => $retPie,
            'ret_lph' => $retLph,
            'ret_fpj' => $retFpj,
            'ordinaria' => $ordinaria,
            'incentivo' => $incentivo,
            'feriado' => $feriado,
            'gastos_representacion' => $gastosRepresentacion,
            'total' => $total,
            'conceptos' => $this->generarConceptos(
                $sueldoBasico,
                $primaProfesionalizacion,
                $primaAntiguedad,
                $primaPorHijo,
                $comida,
                $otrasPrimas,
                $retIvss,
                $retPie,
                $retLph,
                $retFpj,
                $ordinaria,
                $incentivo,
                $feriado,
                $gastosRepresentacion,
                $empleado,
                $nomina
            )
        ];
    }
    
    /**
     * Calculate base salary divided by 2 as requested.
     *
     * @param Empleado $empleado
     * @return float
     */
    protected function calcularSueldoBasico(Empleado $empleado)
    {
        // If the employee has nivel_rango_id, grupo_cargo_id, and tipo_cargo set,
        // we look up the corresponding value in the remuneracion table
        if ($empleado->nivel_rango_id && $empleado->grupo_cargo_id && $empleado->tipo_cargo) {
            $remuneracion = Remuneracion::where([
                'nivel_rango_id' => $empleado->nivel_rango_id,
                'grupo_cargo_id' => $empleado->grupo_cargo_id,
                'tipo_cargo' => $empleado->tipo_cargo,
                'estado' => true
            ])->first();
            
            if ($remuneracion) {
                // Divide by 2 as requested in the requirements
                return round($remuneracion->valor / 2, 2);
            }
        }
        
        // If no remuneracion is found, return the employee's defined salary divided by 2
        return round($empleado->salario / 2, 2);
    }
    
    /**
     * Calculate professionalization bonus.
     *
     * @param Empleado $empleado
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularPrimaProfesionalizacion(Empleado $empleado, $sueldoBasico)
    {
        if (!$empleado->prima_profesionalizacion_id) {
            return 0;
        }
        
        $primaProfesionalizacion = PrimaProfesionalizacion::find($empleado->prima_profesionalizacion_id);
        if (!$primaProfesionalizacion || !$primaProfesionalizacion->estado) {
            return 0;
        }
        
        // Calculate prima based on the percentage of base salary
        return round($sueldoBasico * ($primaProfesionalizacion->porcentaje / 100), 2);
    }
    
    /**
     * Calculate seniority bonus.
     *
     * @param Empleado $empleado
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularPrimaAntiguedad(Empleado $empleado, $sueldoBasico)
    {
        if (!$empleado->prima_antiguedad_id) {
            return 0;
        }
        
        $primaAntiguedad = PrimaAntiguedad::find($empleado->prima_antiguedad_id);
        if (!$primaAntiguedad || !$primaAntiguedad->estado) {
            return 0;
        }
        
        // Calculate prima based on the percentage of base salary
        return round($sueldoBasico * ($primaAntiguedad->porcentaje / 100), 2);
    }
    
    /**
     * Calculate children bonus.
     *
     * @param Empleado $empleado
     * @param Empleado $empleado
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularPrimaPorHijo(Empleado $empleado, $sueldoBasico)
    {
        // Usar la cantidad de hijos del perfil del empleado
        $numeroHijos = (int) ($empleado->cantidad_hijos ?? 0);
        if ($numeroHijos <= 0) {
            return 0.0;
        }

        // Buscar configuración en la tabla prima_hijos con hijos <= cantidad_hijos,
        // tomando el registro con mayor número de hijos dentro de ese rango
        $config = PrimaHijo::where('hijos', '<=', $numeroHijos)
            ->where('estado', true)
            ->orderByDesc('hijos')
            ->first();

        if (!$config) {
            return 0.0;
        }

        // Calcular prima como porcentaje del sueldo básico
        return round($sueldoBasico * ($config->porcentaje / 100), 2);
    }
    
    /**
     * Calculate food allowance.
     *
     * @return float
     */
    protected function calcularComida(Nomina $nomina)
    {
        // Only include if the receipt/payroll period date meets the rule: day > 5 and < 11
        // We use the period end date (fecha_fin) as the reference date for the receipt
        $dia = $nomina->fecha_fin ? (int) $nomina->fecha_fin->day : null;
        if ($dia !== null && $dia >= 5 && $dia <= 11) {
            $beneficio = Deduccion::findActiveBenefit('comida');
            return $beneficio ? $beneficio->getValor() : 24.00; // Valor por defecto
        }
        return 0.00;
    }
    
    /**
     * Calculate other bonuses.
     *
     * @param Empleado $empleado
     * @return float
     */
    protected function calcularOtrasPrimas(Empleado $empleado)
    {
        // Example implementation - adjust as needed
        return 0.00;
    }
    
    /**
     * Calculate IVSS retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetIVSS(Empleado $empleado, $sueldoBasico)
    {
        // Buscar IVSS solo entre las deducciones asignadas al empleado
        $deduccion = $empleado->deducciones()
            ->where('activo', true)
            ->where('nombre', 'IVSS')
            ->first();
            
        if ($deduccion) {
            if ($deduccion->es_fijo) {
                return $deduccion->monto_fijo;
            } else {
                return round($sueldoBasico * ($deduccion->porcentaje / 100), 2);
            }
        }
        
        // Si el empleado no tiene asignada esta deducción, no se aplica ningún cálculo
        return 0.00;
    }
    
    /**
     * Calculate PIE retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetPIE(Empleado $empleado, $sueldoBasico)
    {
        // Buscar PIE solo entre las deducciones asignadas al empleado
        $deduccion = $empleado->deducciones()
            ->where('activo', true)
            ->where('nombre', 'PIE')
            ->first();
            
        if ($deduccion) {
            if ($deduccion->es_fijo) {
                return $deduccion->monto_fijo;
            } else {
                return round($sueldoBasico * ($deduccion->porcentaje / 100), 2);
            }
        }
        
        // Si no hay deducción configurada en BD, no se aplica ningún cálculo
        return 0.00;
    }
    
    /**
     * Calculate LPH retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetLPH(Empleado $empleado, $sueldoBasico)
    {
        // Buscar LPH solo entre las deducciones asignadas al empleado
        $deduccion = $empleado->deducciones()
            ->where('activo', true)
            ->where('nombre', 'LPH')
            ->first();
            
        if ($deduccion) {
            if ($deduccion->es_fijo) {
                return $deduccion->monto_fijo;
            } else {
                return round($sueldoBasico * ($deduccion->porcentaje / 100), 2);
            }
        }
        
        // Si no hay deducción configurada en BD, no se aplica ningún cálculo
        return 0.00;
    }
    
    /**
     * Calculate FPJ retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetFPJ(Empleado $empleado, $sueldoBasico)
    {
        // Buscar FPJ solo entre las deducciones asignadas al empleado
        $deduccion = $empleado->deducciones()
            ->where('activo', true)
            ->where('nombre', 'FPJ')
            ->first();
            
        if ($deduccion) {
            if ($deduccion->es_fijo) {
                return $deduccion->monto_fijo;
            } else {
                return round($sueldoBasico * ($deduccion->porcentaje / 100), 2);
            }
        }
        
        // Si no hay deducción configurada en BD, no se aplica ningún cálculo
        return 0.00;
    }
    
    /**
     * Calculate ordinary bonus.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularOrdinaria($sueldoBasico)
    {
        // ORDINARIA ahora se manejará como un beneficio general vía Beneficio/BeneficioCargo
        return 0.00;
    }
    
    /**
     * Calculate incentive.
     *
     * @param Empleado $empleado
     * @return float
     */
    protected function calcularIncentivo(Empleado $empleado)
    {
        // INCENTIVO ahora se manejará como un beneficio general vía Beneficio/BeneficioCargo
        return 0.00;
    }
    
    /**
     * Calculate holiday pay.
     *
     * @return float
     */
    protected function calcularFeriado()
    {
        // La bonificación por feriado ya no se maneja como beneficio global.
        // Si se requiere en el futuro, debe configurarse como Beneficio + BeneficioCargo.
        return 0.00;
    }
    
    /**
     * Calculate representation expenses.
     *
     * @param Empleado $empleado
     * @return float
     */
    protected function calcularGastosRepresentacion(Empleado $empleado)
    {
        // Check if employee has a position eligible for representation expenses
        if ($empleado->cargo && $empleado->cargo->nivel === 'directivo') {
            $beneficio = Deduccion::findActiveBenefit('rep_direct');
            return $beneficio ? $beneficio->getValor() : 2240.00; // Valor por defecto
        } elseif ($empleado->cargo && $empleado->cargo->nivel === 'gerencial') {
            $beneficio = Deduccion::findActiveBenefit('rep_gerencial');
            return $beneficio ? $beneficio->getValor() : 1500.00; // Valor por defecto
        } elseif ($empleado->cargo && $empleado->cargo->nivel === 'supervisorio') {
            $beneficio = Deduccion::findActiveBenefit('rep_super');
            return $beneficio ? $beneficio->getValor() : 750.00; // Valor por defecto
        }
        
        return 0.00;
    }
    
    // Métodos calcularTxt1..calcularTxt10 eliminados porque los campos txt_1..txt_10
    // ya no se utilizan en el sistema ni en base de datos.
    
    /**
     * Calculate total amount.
     *
     * @param float $sueldoBasico
     * @param float $primaProfesionalizacion
     * @param float $primaAntiguedad
     * @param float $primaPorHijo
     * @param float $comida
     * @param float $otrasPrimas
     * @param float $retIvss
     * @param float $retPie
     * @param float $retLph
     * @param float $retFpj
     * @param float $ordinaria
     * @param float $incentivo
     * @param float $feriado
     * @param float $gastosRepresentacion
     * @return float
     */
    protected function calcularTotal(
        $sueldoBasico,
        $primaProfesionalizacion,
        $primaAntiguedad,
        $primaPorHijo,
        $comida,
        $otrasPrimas,
        $retIvss,
        $retPie,
        $retLph,
        $retFpj,
        $ordinaria,
        $incentivo,
        $feriado,
        $gastosRepresentacion
    ) {
        // Total income (asignaciones)
        $asignaciones = $sueldoBasico + $primaProfesionalizacion + $primaAntiguedad +
                        $primaPorHijo + $comida + $otrasPrimas + $ordinaria +
                        $incentivo + $feriado + $gastosRepresentacion;
        
        // Total deductions (retenciones)
        $deducciones = $retIvss + $retPie + $retLph + $retFpj;
        
        // Net total
        return round($asignaciones - $deducciones, 2);
    }
    
    /**
     * Generate concepts for detailed breakdown.
     *
     * @param float $sueldoBasico
     * @param float $primaProfesionalizacion
     * @param float $primaAntiguedad
     * @param float $primaPorHijo
     * @param float $comida
     * @param float $otrasPrimas
     * @param float $retIvss
     * @param float $retPie
     * @param float $retLph
     * @param float $retFpj
     * @param float $ordinaria
     * @param float $incentivo
     * @param float $feriado
     * @param float $gastosRepresentacion
     * @return array
     */
    protected function generarConceptos(
        $sueldoBasico,
        $primaProfesionalizacion,
        $primaAntiguedad,
        $primaPorHijo,
        $comida,
        $otrasPrimas,
        $retIvss,
        $retPie,
        $retLph,
        $retFpj,
        $ordinaria,
        $incentivo,
        $feriado,
        $gastosRepresentacion,
        Empleado $empleado,
        Nomina $nomina
    ) {
        $conceptos = [];
        
        // Add assignments (ingresos)
        if ($sueldoBasico > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Sueldo Básico',
                'monto' => $sueldoBasico,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        if ($primaProfesionalizacion > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Prima de Profesionalización',
                'monto' => $primaProfesionalizacion,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        if ($primaAntiguedad > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Prima de Antigüedad',
                'monto' => $primaAntiguedad,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        if ($primaPorHijo > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Prima por Hijo',
                'monto' => $primaPorHijo,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        if ($comida > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Comida',
                'monto' => $comida,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        if ($otrasPrimas > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Otras Primas',
                'monto' => $otrasPrimas,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        if ($ordinaria > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Ordinaria',
                'monto' => $ordinaria,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        if ($incentivo > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Incentivo',
                'monto' => $incentivo,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        if ($feriado > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Feriado',
                'monto' => $feriado,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        if ($gastosRepresentacion > 0) {
            $conceptos[] = [
                'tipo' => 'asignacion',
                'descripcion' => 'Gastos de Representación',
                'monto' => $gastosRepresentacion,
                'porcentaje' => null,
                'es_fijo' => true
            ];
        }
        
        // Add deductions (deducciones)
        if ($retIvss > 0) {
            $conceptos[] = [
                'tipo' => 'deduccion',
                'descripcion' => 'Retención IVSS',
                'monto' => $retIvss,
                'porcentaje' => null,
                'es_fijo' => false
            ];
        }
        
        if ($retPie > 0) {
            $conceptos[] = [
                'tipo' => 'deduccion',
                'descripcion' => 'Retención PIE',
                'monto' => $retPie,
                'porcentaje' => null,
                'es_fijo' => false
            ];
        }
        
        if ($retLph > 0) {
            $conceptos[] = [
                'tipo' => 'deduccion',
                'descripcion' => 'Retención LPH',
                'monto' => $retLph,
                'porcentaje' => null,
                'es_fijo' => false
            ];
        }
        
        if ($retFpj > 0) {
            $conceptos[] = [
                'tipo' => 'deduccion',
                'descripcion' => 'Retención FPJ',
                'monto' => $retFpj,
                'porcentaje' => null,
                'es_fijo' => false
            ];
        }
        
        // Add employee-specific benefits and deductions, avoid duplicates
        $existentes = [];
        foreach ($conceptos as $c) {
            $existentes[$c['tipo'].'|'.$c['descripcion']] = true;
        }

        // Obtener rango de días de la nómina (solo el día del mes)
        $desdeDia = $nomina->fecha_inicio ? (int) $nomina->fecha_inicio->day : null;
        $hastaDia = $nomina->fecha_fin ? (int) $nomina->fecha_fin->day : null;

        // Beneficios (asignaciones) asignados al empleado
        $beneficiosEmpleado = $empleado->beneficios()->get();
        foreach ($beneficiosEmpleado as $beneficio) {
            // Filtrar por día de fecha_beneficio: aplica si
            // - fecha_beneficio es null (puede calcularse cualquier día), o
            // - el día de fecha_beneficio está entre desdeDia y hastaDia (inclusive)
            $aplicaPorFecha = true;
            if ($beneficio->fecha_beneficio && $desdeDia !== null && $hastaDia !== null) {
                $diaBeneficio = (int) $beneficio->fecha_beneficio->day;
                if ($diaBeneficio < $desdeDia || $diaBeneficio > $hastaDia) {
                    $aplicaPorFecha = false;
                }
            }

            if (!$aplicaPorFecha) {
                continue;
            }

            $monto = 0;

            // 1) Si el pivote tiene un valor_extra, tiene prioridad
            if (!is_null($beneficio->pivot->valor_extra)) {
                $monto = (float) $beneficio->pivot->valor_extra;
            } else {
                // 2) Buscar configuración por cargo en BeneficioCargo (tipo de cargo del empleado o 'Todos')
                $tipoCargo = $empleado->cargo ? $empleado->cargo->tipo_cargo : null;

                $config = BeneficioCargo::where('beneficio_id', $beneficio->id)
                    ->when($tipoCargo, function ($query) use ($tipoCargo) {
                        $query->whereIn('cargo', [$tipoCargo, 'Todos']);
                    }, function ($query) {
                        $query->where('cargo', 'Todos');
                    })
                    ->orderByRaw("CASE WHEN cargo = ? THEN 0 WHEN cargo = 'Todos' THEN 1 ELSE 2 END", [$tipoCargo])
                    ->first();

                if ($config) {
                    if (!is_null($config->valor)) {
                        $monto = (float) $config->valor;
                    } elseif (!is_null($config->porcentaje)) {
                        $monto = round($sueldoBasico * ($config->porcentaje / 100), 2);
                    }
                }
            }

            if ($monto > 0) {
                $clave = 'asignacion|'.$beneficio->beneficio;
                if (!isset($existentes[$clave])) {
                    $conceptos[] = [
                        'tipo' => 'asignacion',
                        'descripcion' => $beneficio->beneficio,
                        'monto' => $monto,
                        'porcentaje' => null,
                        'es_fijo' => false,
                    ];
                    $existentes[$clave] = true;
                }
            }
        }

        // Deducciones asignadas al empleado
        $deduccionesEmpleado = $empleado->deducciones()->where('activo', true)->get();
        foreach ($deduccionesEmpleado as $ded) {
            // Evitar duplicar las deducciones especiales ya tratadas arriba
            if (in_array($ded->nombre, ['IVSS', 'PIE', 'LPH', 'FPJ'])) {
                continue;
            }
            $monto = 0;
            if (!is_null($ded->pivot->valor_extra)) {
                $monto = (float) $ded->pivot->valor_extra;
            } elseif ($ded->es_fijo) {
                $monto = (float) $ded->monto_fijo;
            } else {
                $monto = round($sueldoBasico * ($ded->porcentaje / 100), 2);
            }

            if ($monto > 0) {
                $clave = 'deduccion|'.$ded->nombre;
                if (!isset($existentes[$clave])) {
                    $conceptos[] = [
                        'tipo' => 'deduccion',
                        'descripcion' => $ded->nombre,
                        'monto' => $monto,
                        'porcentaje' => $ded->es_fijo ? null : $ded->porcentaje,
                        'es_fijo' => $ded->es_fijo
                    ];
                    $existentes[$clave] = true;
                }
            }
        }

        return $conceptos;
    }
}
