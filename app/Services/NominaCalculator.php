<?php

namespace App\Services;

use App\Models\Empleado;
use App\Models\Nomina;
use App\Models\Remuneracion;
use App\Models\PrimaAntiguedad;
use App\Models\PrimaProfesionalizacion;
use App\Models\Deduccion;
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
        $sueldoBasico = $this->calcularSueldoBasico($empleado);
        $primaProfesionalizacion = $this->calcularPrimaProfesionalizacion($empleado, $sueldoBasico);
        $primaAntiguedad = $this->calcularPrimaAntiguedad($empleado, $sueldoBasico);
        $primaPorHijo = $this->calcularPrimaPorHijo($empleado);
        $comida = $this->calcularComida($nomina);
        $otrasPrimas = $this->calcularOtrasPrimas($empleado);
        
        // Retenciones (deductions)
        $retIvss = $this->calcularRetIVSS($sueldoBasico);
        $retPie = $this->calcularRetPIE($sueldoBasico);
        $retLph = $this->calcularRetLPH($sueldoBasico);
        $retFpj = $this->calcularRetFPJ($sueldoBasico);
        
        // Asignaciones adicionales
        $ordinaria = $this->calcularOrdinaria($sueldoBasico);
        $incentivo = $this->calcularIncentivo($empleado);
        $feriado = $this->calcularFeriado();
        $gastosRepresentacion = $this->calcularGastosRepresentacion($empleado);
        
        // Additional text fields (may be used for special bonuses or calculations)
        $txt1 = $this->calcularTxt1();
        $txt2 = $this->calcularTxt2();
        $txt3 = $this->calcularTxt3();
        $txt4 = $this->calcularTxt4();
        $txt5 = $this->calcularTxt5();
        $txt6 = $this->calcularTxt6();
        $txt7 = $this->calcularTxt7();
        $txt8 = $this->calcularTxt8();
        $txt9 = $this->calcularTxt9();
        $txt10 = $this->calcularTxt10();
        
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
            'txt_1' => $txt1,
            'txt_2' => $txt2,
            'txt_3' => $txt3,
            'txt_4' => $txt4,
            'txt_5' => $txt5,
            'txt_6' => $txt6,
            'txt_7' => $txt7,
            'txt_8' => $txt8,
            'txt_9' => $txt9,
            'txt_10' => $txt10,
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
                $empleado
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
     * @return float
     */
    protected function calcularPrimaPorHijo(Empleado $empleado)
    {
        // Get the amount per child from the unified configuration
        $beneficio = Deduccion::findActiveBenefit('prima_por_hijo');
        $montoPorHijo = $beneficio ? $beneficio->getValor() : 6.25; // Valor por defecto
        $numeroHijos = $empleado->numero_hijos ?? 0;
        
        return round($montoPorHijo * $numeroHijos, 2);
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
        if ($dia !== null && $dia > 5 && $dia < 11) {
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
    protected function calcularRetIVSS($sueldoBasico)
    {
        // Get IVSS percentage from existing deduction table
        $deduccion = Deduccion::where('nombre', 'IVSS')
            ->where('activo', true)
            ->first();
            
        if ($deduccion) {
            if ($deduccion->es_fijo) {
                return $deduccion->monto_fijo;
            } else {
                return round($sueldoBasico * ($deduccion->porcentaje / 100), 2);
            }
        }
        
        // Fallback to default 4%
        return round($sueldoBasico * 0.04, 2);
    }
    
    /**
     * Calculate PIE retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetPIE($sueldoBasico)
    {
        // Get PIE percentage from existing deduction table
        $deduccion = Deduccion::where('nombre', 'PIE')
            ->where('activo', true)
            ->first();
            
        if ($deduccion) {
            if ($deduccion->es_fijo) {
                return $deduccion->monto_fijo;
            } else {
                return round($sueldoBasico * ($deduccion->porcentaje / 100), 2);
            }
        }
        
        // Fallback to default 0.5%
        return round($sueldoBasico * 0.005, 2);
    }
    
    /**
     * Calculate LPH retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetLPH($sueldoBasico)
    {
        // Get LPH percentage from existing deduction table
        $deduccion = Deduccion::where('nombre', 'LPH')
            ->where('activo', true)
            ->first();
            
        if ($deduccion) {
            if ($deduccion->es_fijo) {
                return $deduccion->monto_fijo;
            } else {
                return round($sueldoBasico * ($deduccion->porcentaje / 100), 2);
            }
        }
        
        // Fallback to default 1%
        return round($sueldoBasico * 0.01, 2);
    }
    
    /**
     * Calculate FPJ retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetFPJ($sueldoBasico)
    {
        // Get FPJ percentage from existing deduction table
        $deduccion = Deduccion::where('nombre', 'FPJ')
            ->where('activo', true)
            ->first();
            
        if ($deduccion) {
            if ($deduccion->es_fijo) {
                return $deduccion->monto_fijo;
            } else {
                return round($sueldoBasico * ($deduccion->porcentaje / 100), 2);
            }
        }
        
        // Fallback to default 2%
        return round($sueldoBasico * 0.02, 2);
    }
    
    /**
     * Calculate ordinary bonus.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularOrdinaria($sueldoBasico)
    {
        $beneficio = Deduccion::findActiveBenefit('ordinaria');
        
        if ($beneficio) {
            if ($beneficio->es_fijo) {
                // Apply fixed amount
                return $beneficio->monto_fijo;
            } else {
                // Apply percentage of base salary
                return round($sueldoBasico * ($beneficio->porcentaje / 100), 2);
            }
        }
        
        // Default fallback (150% of base salary)
        return round($sueldoBasico * 1.5, 2);
    }
    
    /**
     * Calculate incentive.
     *
     * @param Empleado $empleado
     * @return float
     */
    protected function calcularIncentivo(Empleado $empleado)
    {
        // Get incentive from unified configuration
        $beneficio = Deduccion::findActiveBenefit('incentivo');
        return $beneficio ? $beneficio->getValor() : 1000.00; // Valor por defecto
    }
    
    /**
     * Calculate holiday pay.
     *
     * @return float
     */
    protected function calcularFeriado()
    {
        // Get holiday pay from unified configuration
        $beneficio = Deduccion::findActiveBenefit('feriado');
        return $beneficio ? $beneficio->getValor() : 1210.00; // Valor por defecto
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
    
    /**
     * Calculate TXT1 field value.
     *
     * @return float
     */
    protected function calcularTxt1()
    {
        // Get TXT1 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_1');
        return $parametro ? $parametro->getValor() : 600.00; // Valor por defecto
    }
    
    /**
     * Calculate TXT2 field value.
     *
     * @return float
     */
    protected function calcularTxt2()
    {
        // Get TXT2 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_2');
        return $parametro ? $parametro->getValor() : 590.00; // Valor por defecto
    }
    
    /**
     * Calculate TXT3 field value.
     *
     * @return float
     */
    protected function calcularTxt3()
    {
        // Get TXT3 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_3');
        return $parametro ? $parametro->getValor() : 580.00; // Valor por defecto
    }
    
    /**
     * Calculate TXT4 field value.
     *
     * @return float
     */
    protected function calcularTxt4()
    {
        // Get TXT4 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_4');
        return $parametro ? $parametro->getValor() : 570.00; // Valor por defecto
    }
    
    /**
     * Calculate TXT5 field value.
     *
     * @return float
     */
    protected function calcularTxt5()
    {
        // Get TXT5 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_5');
        return $parametro ? $parametro->getValor() : 560.00; // Valor por defecto
    }
    
    /**
     * Calculate TXT6 field value.
     *
     * @return float
     */
    protected function calcularTxt6()
    {
        // Get TXT6 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_6');
        return $parametro ? $parametro->getValor() : 550.00; // Valor por defecto
    }
    
    /**
     * Calculate TXT7 field value.
     *
     * @return float
     */
    protected function calcularTxt7()
    {
        // Get TXT7 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_7');
        return $parametro ? $parametro->getValor() : 0.00; // Valor por defecto
    }
    
    /**
     * Calculate TXT8 field value.
     *
     * @return float
     */
    protected function calcularTxt8()
    {
        // Get TXT8 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_8');
        return $parametro ? $parametro->getValor() : 0.00; // Valor por defecto
    }
    
    /**
     * Calculate TXT9 field value.
     *
     * @return float
     */
    protected function calcularTxt9()
    {
        // Get TXT9 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_9');
        return $parametro ? $parametro->getValor() : 0.00; // Valor por defecto
    }
    
    /**
     * Calculate TXT10 field value.
     *
     * @return float
     */
    protected function calcularTxt10()
    {
        // Get TXT10 value from unified configuration
        $parametro = Deduccion::findActiveParameter('txt_10');
        return $parametro ? $parametro->getValor() : 0.00; // Valor por defecto
    }
    
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
        Empleado $empleado
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
                'porcentaje' => 4, // 4% IVSS
                'es_fijo' => false
            ];
        }
        
        if ($retPie > 0) {
            $conceptos[] = [
                'tipo' => 'deduccion',
                'descripcion' => 'Retención PIE',
                'monto' => $retPie,
                'porcentaje' => 0.5, // 0.5% PIE
                'es_fijo' => false
            ];
        }
        
        if ($retLph > 0) {
            $conceptos[] = [
                'tipo' => 'deduccion',
                'descripcion' => 'Retención LPH',
                'monto' => $retLph,
                'porcentaje' => 1, // 1% LPH
                'es_fijo' => false
            ];
        }
        
        if ($retFpj > 0) {
            $conceptos[] = [
                'tipo' => 'deduccion',
                'descripcion' => 'Retención FPJ',
                'monto' => $retFpj,
                'porcentaje' => 2, // 2% FPJ (example)
                'es_fijo' => false
            ];
        }
        
        // Add employee-specific benefits and deductions, avoid duplicates
        $existentes = [];
        foreach ($conceptos as $c) {
            $existentes[$c['tipo'].'|'.$c['descripcion']] = true;
        }

        // Beneficios (asignaciones) asignados al empleado
        $beneficiosEmpleado = $empleado->beneficios()->where('activo', true)->get();
        foreach ($beneficiosEmpleado as $beneficio) {
            $monto = 0;
            if (!is_null($beneficio->pivot->valor_extra)) {
                $monto = (float) $beneficio->pivot->valor_extra;
            } elseif ($beneficio->es_fijo) {
                $monto = (float) $beneficio->monto_fijo;
            } else {
                $monto = round($sueldoBasico * ($beneficio->porcentaje / 100), 2);
            }

            if ($monto > 0) {
                $clave = 'asignacion|'.$beneficio->nombre;
                if (!isset($existentes[$clave])) {
                    $conceptos[] = [
                        'tipo' => 'asignacion',
                        'descripcion' => $beneficio->nombre,
                        'monto' => $monto,
                        'porcentaje' => $beneficio->es_fijo ? null : $beneficio->porcentaje,
                        'es_fijo' => $beneficio->es_fijo
                    ];
                    $existentes[$clave] = true;
                }
            }
        }

        // Deducciones asignadas al empleado
        $deduccionesEmpleado = $empleado->deducciones()->where('activo', true)->get();
        foreach ($deduccionesEmpleado as $ded) {
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
