<?php

// Bootstrap the Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the connection name from config
$connectionName = config('database.default');
echo "Using database connection: " . $connectionName . "\n";
echo "Database name: " . config("database.connections.{$connectionName}.database") . "\n";

try {
    // Only seed the tables with more concise values

    // Deduction configs
    $deductions = [
        [
            'codigo' => 'ivss',
            'nombre' => 'IVSS',
            'descripcion' => 'Instituto Venezolano de los Seguros Sociales',
            'porcentaje' => 4.0000,
            'activo' => true,
        ],
        [
            'codigo' => 'pie',
            'nombre' => 'PIE',
            'descripcion' => 'Paro forzoso',
            'porcentaje' => 0.5000,
            'activo' => true,
        ],
        [
            'codigo' => 'lph',
            'nombre' => 'LPH',
            'descripcion' => 'Ley de Política Habitacional',
            'porcentaje' => 1.0000,
            'activo' => true,
        ],
        [
            'codigo' => 'fpj',
            'nombre' => 'FPJ',
            'descripcion' => 'Fondo de Pensiones Judiciales',
            'porcentaje' => 2.0000,
            'activo' => true,
        ],
    ];

    foreach ($deductions as $deduction) {
        DB::table('deduction_configs')->updateOrInsert(
            ['codigo' => $deduction['codigo']],
            array_merge($deduction, ['created_at' => now(), 'updated_at' => now()])
        );
    }
    echo "Seeded deduction_configs table\n";

    // Benefit configs - shortened descriptions to avoid truncation
    $benefits = [
        [
            'codigo' => 'prima_hijo',
            'nombre' => 'Prima por Hijo',
            'descripcion' => 'Monto por cada hijo',
            'valor' => 6.25,
            'tipo' => 'fijo',
            'activo' => true,
        ],
        [
            'codigo' => 'comida',
            'nombre' => 'Bono Alimentación',
            'descripcion' => 'Bono para alimentación',
            'valor' => 24.00,
            'tipo' => 'fijo',
            'activo' => true,
        ],
        [
            'codigo' => 'ordinaria',
            'nombre' => 'Bonificación',
            'descripcion' => 'Porcentaje sobre sueldo',
            'valor' => 150.00,
            'tipo' => 'porcentaje',
            'activo' => true,
        ],
        [
            'codigo' => 'incentivo',
            'nombre' => 'Incentivo',
            'descripcion' => 'Incentivo para empleados',
            'valor' => 1000.00,
            'tipo' => 'fijo',
            'activo' => true,
        ],
        [
            'codigo' => 'feriado',
            'nombre' => 'Bono Feriado',
            'descripcion' => 'Pago por días feriados',
            'valor' => 1210.00,
            'tipo' => 'fijo',
            'activo' => true,
        ],
        [
            'codigo' => 'rep_direct',
            'nombre' => 'Gastos Rep. Directivo',
            'descripcion' => 'Gastos nivel directivo',
            'valor' => 2240.00,
            'tipo' => 'fijo',
            'activo' => true,
        ],
        [
            'codigo' => 'rep_gerencial',
            'nombre' => 'Gastos Rep. Gerencial',
            'descripcion' => 'Gastos nivel gerencial',
            'valor' => 1500.00,
            'tipo' => 'fijo',
            'activo' => true,
        ],
        [
            'codigo' => 'rep_super',
            'nombre' => 'Gastos Rep. Supervisor',
            'descripcion' => 'Gastos nivel supervisorio',
            'valor' => 750.00,
            'tipo' => 'fijo',
            'activo' => true,
        ],
    ];

    foreach ($benefits as $benefit) {
        DB::table('benefit_configs')->updateOrInsert(
            ['codigo' => $benefit['codigo']],
            array_merge($benefit, ['created_at' => now(), 'updated_at' => now()])
        );
    }
    echo "Seeded benefit_configs table\n";

    // Payroll parameters
    $parameters = [
        [
            'codigo' => 'txt1',
            'nombre' => 'Bono Asistencia',
            'descripcion' => 'Asistencia perfecta',
            'campo' => 'txt_1',
            'valor_defecto' => 600.00,
            'activo' => true,
        ],
        [
            'codigo' => 'txt2',
            'nombre' => 'Bono Desempeño',
            'descripcion' => 'Desempeño excepcional',
            'campo' => 'txt_2',
            'valor_defecto' => 590.00,
            'activo' => true,
        ],
        [
            'codigo' => 'txt3',
            'nombre' => 'Bono Productividad',
            'descripcion' => 'Cumplimiento de metas',
            'campo' => 'txt_3',
            'valor_defecto' => 580.00,
            'activo' => true,
        ],
        [
            'codigo' => 'txt4',
            'nombre' => 'Bono Transporte',
            'descripcion' => 'Subsidio transporte',
            'campo' => 'txt_4',
            'valor_defecto' => 570.00,
            'activo' => true,
        ],
        [
            'codigo' => 'txt5',
            'nombre' => 'Bono Salud',
            'descripcion' => 'Subsidio médico',
            'campo' => 'txt_5',
            'valor_defecto' => 560.00,
            'activo' => true,
        ],
        [
            'codigo' => 'txt6',
            'nombre' => 'Bono Educación',
            'descripcion' => 'Subsidio educativo',
            'campo' => 'txt_6',
            'valor_defecto' => 550.00,
            'activo' => true,
        ],
        [
            'codigo' => 'txt7',
            'nombre' => 'Complemento',
            'descripcion' => 'Complemento salarial',
            'campo' => 'txt_7',
            'valor_defecto' => 0.00,
            'activo' => true,
        ],
        [
            'codigo' => 'txt8',
            'nombre' => 'Reembolso',
            'descripcion' => 'Reembolso de gastos',
            'campo' => 'txt_8',
            'valor_defecto' => 0.00,
            'activo' => true,
        ],
        [
            'codigo' => 'txt9',
            'nombre' => 'Bono Méritos',
            'descripcion' => 'Bono por méritos',
            'campo' => 'txt_9',
            'valor_defecto' => 0.00,
            'activo' => true,
        ],
        [
            'codigo' => 'txt10',
            'nombre' => 'Compensación',
            'descripcion' => 'Compensación adicional',
            'campo' => 'txt_10',
            'valor_defecto' => 0.00,
            'activo' => true,
        ],
    ];

    foreach ($parameters as $parameter) {
        DB::table('payroll_parameters')->updateOrInsert(
            ['codigo' => $parameter['codigo']],
            array_merge($parameter, ['created_at' => now(), 'updated_at' => now()])
        );
    }
    echo "Seeded payroll_parameters table\n";

    echo "All tables seeded successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
