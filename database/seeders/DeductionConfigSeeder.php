<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeductionConfig;

class DeductionConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deductions = [
            [
                'codigo' => 'ivss',
                'nombre' => 'IVSS',
                'descripcion' => 'Instituto Venezolano de los Seguros Sociales',
                'porcentaje' => 4.0000, // 4%
                'activo' => true,
            ],
            [
                'codigo' => 'pie',
                'nombre' => 'PIE',
                'descripcion' => 'Paro forzoso',
                'porcentaje' => 0.5000, // 0.5%
                'activo' => true,
            ],
            [
                'codigo' => 'lph',
                'nombre' => 'LPH',
                'descripcion' => 'Ley de Política Habitacional',
                'porcentaje' => 1.0000, // 1%
                'activo' => true,
            ],
            [
                'codigo' => 'fpj',
                'nombre' => 'FPJ',
                'descripcion' => 'Fondo de Pensiones Judiciales',
                'porcentaje' => 2.0000, // 2%
                'activo' => true,
            ],
        ];

        foreach ($deductions as $deduction) {
            DeductionConfig::updateOrCreate(
                ['codigo' => $deduction['codigo']],
                $deduction
            );
        }
    }
}
