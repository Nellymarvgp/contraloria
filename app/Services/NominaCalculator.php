<?php

namespace App\Services;

use App\Models\Empleado;
use App\Models\Remuneracion;
use App\Models\PrimaAntiguedad;
use App\Models\PrimaProfesionalizacion;
use App\Models\Deduccion;
use App\Models\DeductionConfig;
use App\Models\BenefitConfig;
use App\Models\PayrollParameter;
use Carbon\Carbon;

class NominaCalculator
{
    /**
     * Calculate payroll for an employee.
     *
     * @param Empleado $empleado
     * @return array
     */
    public function calculate(Empleado $empleado)
    {
        // Base calculations
        $sueldoBasico = $this->calcularSueldoBasico($empleado);
        $primaProfesionalizacion = $this->calcularPrimaProfesionalizacion($empleado, $sueldoBasico);
        $primaAntiguedad = $this->calcularPrimaAntiguedad($empleado, $sueldoBasico);
        $primaPorHijo = $this->calcularPrimaPorHijo($empleado);
        $comida = $this->calcularComida();
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
                $gastosRepresentacion
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
        // Get the amount per child from the configuration
        $montoPorHijo = BenefitConfig::getActiveValue('prima_por_hijo');
        $numeroHijos = $empleado->numero_hijos ?? 0;
        
        return round($montoPorHijo * $numeroHijos, 2);
    }
    
    /**
     * Calculate food allowance.
     *
     * @return float
     */
    protected function calcularComida()
    {
        // Get food allowance from configuration
        return BenefitConfig::getActiveValue('comida');
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
        // Get IVSS percentage from configuration
        $porcentaje = DeductionConfig::getActivePercentage('ivss') / 100;
        return round($sueldoBasico * $porcentaje, 2);
    }
    
    /**
     * Calculate PIE retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetPIE($sueldoBasico)
    {
        // Get PIE percentage from configuration
        $porcentaje = DeductionConfig::getActivePercentage('pie') / 100;
        return round($sueldoBasico * $porcentaje, 2);
    }
    
    /**
     * Calculate LPH retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetLPH($sueldoBasico)
    {
        // Get LPH percentage from configuration
        $porcentaje = DeductionConfig::getActivePercentage('lph') / 100;
        return round($sueldoBasico * $porcentaje, 2);
    }
    
    /**
     * Calculate FPJ retention.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularRetFPJ($sueldoBasico)
    {
        // Get FPJ percentage from configuration
        $porcentaje = DeductionConfig::getActivePercentage('fpj') / 100;
        return round($sueldoBasico * $porcentaje, 2);
    }
    
    /**
     * Calculate ordinary bonus.
     *
     * @param float $sueldoBasico
     * @return float
     */
    protected function calcularOrdinaria($sueldoBasico)
    {
        $config = BenefitConfig::getActive('ordinaria');
        if ($config && $config->tipo === 'porcentaje') {
            // Apply percentage of base salary
            return round($sueldoBasico * ($config->valor / 100), 2);
        } else if ($config) {
            // Apply fixed amount
            return $config->valor;
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
        // Get incentive from configuration
        return BenefitConfig::getActiveValue('incentivo');
    }
    
    /**
     * Calculate holiday pay.
     *
     * @return float
     */
    protected function calcularFeriado()
    {
        // Get holiday pay from configuration
        return BenefitConfig::getActiveValue('feriado');
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
            return BenefitConfig::getActiveValue('gastos_representacion_directivo');
        } elseif ($empleado->cargo && $empleado->cargo->nivel === 'gerencial') {
            return BenefitConfig::getActiveValue('gastos_representacion_gerencial');
        } elseif ($empleado->cargo && $empleado->cargo->nivel === 'supervisorio') {
            return BenefitConfig::getActiveValue('gastos_representacion_supervisorio');
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
        // Get TXT1 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_1');
    }
    
    /**
     * Calculate TXT2 field value.
     *
     * @return float
     */
    protected function calcularTxt2()
    {
        // Get TXT2 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_2');
    }
    
    /**
     * Calculate TXT3 field value.
     *
     * @return float
     */
    protected function calcularTxt3()
    {
        // Get TXT3 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_3');
    }
    
    /**
     * Calculate TXT4 field value.
     *
     * @return float
     */
    protected function calcularTxt4()
    {
        // Get TXT4 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_4');
    }
    
    /**
     * Calculate TXT5 field value.
     *
     * @return float
     */
    protected function calcularTxt5()
    {
        // Get TXT5 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_5');
    }
    
    /**
     * Calculate TXT6 field value.
     *
     * @return float
     */
    protected function calcularTxt6()
    {
        // Get TXT6 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_6');
    }
    
    /**
     * Calculate TXT7 field value.
     *
     * @return float
     */
    protected function calcularTxt7()
    {
        // Get TXT7 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_7');
    }
    
    /**
     * Calculate TXT8 field value.
     *
     * @return float
     */
    protected function calcularTxt8()
    {
        // Get TXT8 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_8');
    }
    
    /**
     * Calculate TXT9 field value.
     *
     * @return float
     */
    protected function calcularTxt9()
    {
        // Get TXT9 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_9');
    }
    
    /**
     * Calculate TXT10 field value.
     *
     * @return float
     */
    protected function calcularTxt10()
    {
        // Get TXT10 value from PayrollParameter configuration
        return PayrollParameter::getValueByField('txt_10');
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
        $gastosRepresentacion
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
        
        // Add dynamic deductions from the database
        $deducciones = Deduccion::where('activo', true)->get();
        foreach ($deducciones as $deduccion) {
            $monto = 0;
            
            if ($deduccion->es_fijo) {
                $monto = $deduccion->monto_fijo;
            } else {
                $monto = round($sueldoBasico * ($deduccion->porcentaje / 100), 2);
            }
            
            if ($monto > 0) {
                $conceptos[] = [
                    'tipo' => 'deduccion',
                    'descripcion' => $deduccion->nombre,
                    'monto' => $monto,
                    'porcentaje' => $deduccion->es_fijo ? null : $deduccion->porcentaje,
                    'es_fijo' => $deduccion->es_fijo
                ];
            }
        }
        
        return $conceptos;
    }
}
