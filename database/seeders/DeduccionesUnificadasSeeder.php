<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deduccion;

class DeduccionesUnificadasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar y actualizar deducciones existentes para establecer su tipo
        $deducciones = Deduccion::where('tipo', '=', 'deduccion')
            ->orWhereNull('tipo')
            ->get();
        
        foreach ($deducciones as $deduccion) {
            $deduccion->tipo = 'deduccion';
            $deduccion->save();
        }
        
        // Agregar beneficios
        $beneficios = [
            [
                'nombre' => 'prima_por_hijo',
                'tipo' => 'beneficio',
                'descripcion' => 'Monto por cada hijo',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 6.25,
                'activo' => true,
            ],
            [
                'nombre' => 'comida',
                'tipo' => 'beneficio',
                'descripcion' => 'Bono para alimentación',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 24.00,
                'activo' => true,
            ],
            [
                'nombre' => 'ordinaria',
                'tipo' => 'beneficio',
                'descripcion' => 'Porcentaje sobre sueldo',
                'porcentaje' => 150.00,
                'es_fijo' => false,
                'monto_fijo' => 0,
                'activo' => true,
            ],
            [
                'nombre' => 'incentivo',
                'tipo' => 'beneficio',
                'descripcion' => 'Incentivo para empleados',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 1000.00,
                'activo' => true,
            ],
            [
                'nombre' => 'feriado',
                'tipo' => 'beneficio',
                'descripcion' => 'Pago por días feriados',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 1210.00,
                'activo' => true,
            ],
            [
                'nombre' => 'rep_direct',
                'tipo' => 'beneficio',
                'descripcion' => 'Gastos nivel directivo',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 2240.00,
                'activo' => true,
            ],
            [
                'nombre' => 'rep_gerencial',
                'tipo' => 'beneficio',
                'descripcion' => 'Gastos nivel gerencial',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 1500.00,
                'activo' => true,
            ],
            [
                'nombre' => 'rep_super',
                'tipo' => 'beneficio',
                'descripcion' => 'Gastos nivel supervisorio',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 750.00,
                'activo' => true,
            ],
        ];

        foreach ($beneficios as $beneficio) {
            Deduccion::updateOrCreate(
                [
                    'nombre' => $beneficio['nombre'],
                    'tipo' => 'beneficio'
                ],
                $beneficio
            );
        }
        
        // Agregar parámetros de nómina (TXT1-TXT10)
        $parametros = [
            [
                'nombre' => 'Bono Asistencia',
                'tipo' => 'parametro',
                'descripcion' => 'Asistencia perfecta',
                'campo' => 'txt_1',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 600.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Bono Desempeño',
                'tipo' => 'parametro',
                'descripcion' => 'Desempeño excepcional',
                'campo' => 'txt_2',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 590.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Bono Productividad',
                'tipo' => 'parametro',
                'descripcion' => 'Cumplimiento de metas',
                'campo' => 'txt_3',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 580.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Bono Transporte',
                'tipo' => 'parametro',
                'descripcion' => 'Subsidio transporte',
                'campo' => 'txt_4',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 570.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Bono Salud',
                'tipo' => 'parametro',
                'descripcion' => 'Subsidio médico',
                'campo' => 'txt_5',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 560.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Bono Educación',
                'tipo' => 'parametro',
                'descripcion' => 'Subsidio educativo',
                'campo' => 'txt_6',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 550.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Complemento',
                'tipo' => 'parametro',
                'descripcion' => 'Complemento salarial',
                'campo' => 'txt_7',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 0.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Reembolso',
                'tipo' => 'parametro',
                'descripcion' => 'Reembolso de gastos',
                'campo' => 'txt_8',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 0.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Bono Méritos',
                'tipo' => 'parametro',
                'descripcion' => 'Bono por méritos',
                'campo' => 'txt_9',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 0.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Compensación',
                'tipo' => 'parametro',
                'descripcion' => 'Compensación adicional',
                'campo' => 'txt_10',
                'porcentaje' => 0,
                'es_fijo' => true,
                'monto_fijo' => 0.00,
                'activo' => true,
            ],
        ];

        foreach ($parametros as $parametro) {
            Deduccion::updateOrCreate(
                [
                    'campo' => $parametro['campo'],
                    'tipo' => 'parametro'
                ],
                $parametro
            );
        }
    }
}
