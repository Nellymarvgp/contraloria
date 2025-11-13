<?php

// Bootstrap the Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the connection name from config
$connectionName = config('database.default');
echo "Using database connection: " . $connectionName . "\n";
echo "Database name: " . config("database.connections.{$connectionName}.database") . "\n";

// Create the tables manually
try {
    // Create deduction_configs table
    if (!Schema::hasTable('deduction_configs')) {
        Schema::create('deduction_configs', function ($table) {
            $table->id();
            $table->string('codigo', 20);
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->decimal('porcentaje', 8, 4);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        echo "Created deduction_configs table\n";
    } else {
        echo "deduction_configs table already exists\n";
    }

    // Create benefit_configs table
    if (!Schema::hasTable('benefit_configs')) {
        Schema::create('benefit_configs', function ($table) {
            $table->id();
            $table->string('codigo', 20);
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->decimal('valor', 12, 2);
            $table->string('tipo', 20)->comment('fijo, porcentaje'); // Whether it's a fixed amount or percentage
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        echo "Created benefit_configs table\n";
    } else {
        echo "benefit_configs table already exists\n";
    }

    // Create payroll_parameters table
    if (!Schema::hasTable('payroll_parameters')) {
        Schema::create('payroll_parameters', function ($table) {
            $table->id();
            $table->string('codigo', 20);
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->string('campo', 20)->comment('txt_1, txt_2, etc.');
            $table->decimal('valor_defecto', 12, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        echo "Created payroll_parameters table\n";
    } else {
        echo "payroll_parameters table already exists\n";
    }

    // Seed the tables with default values
    // Deduction configs
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
        DB::table('deduction_configs')->updateOrInsert(
            ['codigo' => $deduction['codigo']],
            $deduction
        );
    }
    echo "Seeded deduction_configs table\n";

    // Benefit configs
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
        DB::table('benefit_configs')->updateOrInsert(
            ['codigo' => $benefit['codigo']],
            $benefit
        );
    }
    echo "Seeded benefit_configs table\n";

    // Payroll parameters
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
        DB::table('payroll_parameters')->updateOrInsert(
            ['codigo' => $parameter['codigo']],
            $parameter
        );
    }
    echo "Seeded payroll_parameters table\n";

    echo "All tables created and seeded successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
