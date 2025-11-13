<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BenefitConfig;

class BenefitConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $benefits = [
            [
                'codigo' => 'prima_por_hijo',
                'nombre' => 'Prima por Hijo',
                'descripcion' => 'Monto por cada hijo del empleado',
                'valor' => 6.25,
                'tipo' => 'fijo',
                'activo' => true,
            ],
            [
                'codigo' => 'comida',
                'nombre' => 'Bono de Alimentación',
                'descripcion' => 'Bono diario para alimentación',
                'valor' => 24.00,
                'tipo' => 'fijo',
                'activo' => true,
            ],
            [
                'codigo' => 'ordinaria',
                'nombre' => 'Bonificación Ordinaria',
                'descripcion' => 'Porcentaje sobre el sueldo básico',
                'valor' => 150.00, // 150%
                'tipo' => 'porcentaje',
                'activo' => true,
            ],
            [
                'codigo' => 'incentivo',
                'nombre' => 'Incentivo',
                'descripcion' => 'Incentivo fijo para todos los empleados',
                'valor' => 1000.00,
                'tipo' => 'fijo',
                'activo' => true,
            ],
            [
                'codigo' => 'feriado',
                'nombre' => 'Bono de Feriado',
                'descripcion' => 'Pago adicional por días feriados',
                'valor' => 1210.00,
                'tipo' => 'fijo',
                'activo' => true,
            ],
            [
                'codigo' => 'gastos_representacion_directivo',
                'nombre' => 'Gastos de Representación para Directivos',
                'descripcion' => 'Monto para gastos de representación nivel directivo',
                'valor' => 2240.00,
                'tipo' => 'fijo',
                'activo' => true,
            ],
            [
                'codigo' => 'gastos_representacion_gerencial',
                'nombre' => 'Gastos de Representación para Gerentes',
                'descripcion' => 'Monto para gastos de representación nivel gerencial',
                'valor' => 1500.00,
                'tipo' => 'fijo',
                'activo' => true,
            ],
            [
                'codigo' => 'gastos_representacion_supervisorio',
                'nombre' => 'Gastos de Representación para Supervisores',
                'descripcion' => 'Monto para gastos de representación nivel supervisorio',
                'valor' => 750.00,
                'tipo' => 'fijo',
                'activo' => true,
            ],
        ];

        foreach ($benefits as $benefit) {
            BenefitConfig::updateOrCreate(
                ['codigo' => $benefit['codigo']],
                $benefit
            );
        }
    }
}
