<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PayrollParameter;

class PayrollParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parameters = [
            [
                'codigo' => 'txt1',
                'nombre' => 'Bono de Asistencia',
                'descripcion' => 'Bono por asistencia perfecta',
                'campo' => 'txt_1',
                'valor_defecto' => 600.00,
                'activo' => true,
            ],
            [
                'codigo' => 'txt2',
                'nombre' => 'Bono de Desempeño',
                'descripcion' => 'Bono por desempeño excepcional',
                'campo' => 'txt_2',
                'valor_defecto' => 590.00,
                'activo' => true,
            ],
            [
                'codigo' => 'txt3',
                'nombre' => 'Bono de Productividad',
                'descripcion' => 'Bono por cumplimiento de metas',
                'campo' => 'txt_3',
                'valor_defecto' => 580.00,
                'activo' => true,
            ],
            [
                'codigo' => 'txt4',
                'nombre' => 'Bono de Transporte',
                'descripcion' => 'Subsidio para transporte',
                'campo' => 'txt_4',
                'valor_defecto' => 570.00,
                'activo' => true,
            ],
            [
                'codigo' => 'txt5',
                'nombre' => 'Bono de Salud',
                'descripcion' => 'Subsidio para gastos médicos',
                'campo' => 'txt_5',
                'valor_defecto' => 560.00,
                'activo' => true,
            ],
            [
                'codigo' => 'txt6',
                'nombre' => 'Bono de Educación',
                'descripcion' => 'Subsidio para gastos educativos',
                'campo' => 'txt_6',
                'valor_defecto' => 550.00,
                'activo' => true,
            ],
            [
                'codigo' => 'txt7',
                'nombre' => 'Complemento Especial',
                'descripcion' => 'Complemento salarial especial',
                'campo' => 'txt_7',
                'valor_defecto' => 0.00,
                'activo' => true,
            ],
            [
                'codigo' => 'txt8',
                'nombre' => 'Reembolso de Gastos',
                'descripcion' => 'Reembolso de gastos aprobados',
                'campo' => 'txt_8',
                'valor_defecto' => 0.00,
                'activo' => true,
            ],
            [
                'codigo' => 'txt9',
                'nombre' => 'Bono por Méritos',
                'descripcion' => 'Bono por méritos especiales',
                'campo' => 'txt_9',
                'valor_defecto' => 0.00,
                'activo' => true,
            ],
            [
                'codigo' => 'txt10',
                'nombre' => 'Compensación Adicional',
                'descripcion' => 'Compensación adicional por asignaciones especiales',
                'campo' => 'txt_10',
                'valor_defecto' => 0.00,
                'activo' => true,
            ],
        ];

        foreach ($parameters as $parameter) {
            PayrollParameter::updateOrCreate(
                ['codigo' => $parameter['codigo']],
                $parameter
            );
        }
    }
}
