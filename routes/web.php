<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\PrimaAntiguedadController;
use App\Http\Controllers\PrimaHijoController;
use App\Http\Controllers\PrimaProfesionalizacionController;
use App\Http\Controllers\NivelRangoController;
use App\Http\Controllers\GrupoCargoController;
use App\Http\Controllers\RemuneracionController;
use App\Http\Controllers\RemuneracionApiController;
use App\Http\Controllers\DeduccionController;
use App\Http\Controllers\DeductionConfigController;
use App\Http\Controllers\BenefitConfigController;
use App\Http\Controllers\PayrollParameterController;
use App\Http\Controllers\BeneficioCargoController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\NominaController;
use App\Http\Controllers\VacacionController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\ReciboApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $noticias = \App\Models\Noticia::where('publicado', true)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    return view('landing', compact('noticias'));
})->name('home');

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});
Route::prefix('api')->group(function () {
        // Ruta de prueba mínima
        Route::post('test-login', function (\Illuminate\Http\Request $request) {
            return response()->json([
                'ok' => true,
                'data' => $request->all(),
            ]);
        });
        Route::get('csrf-token', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'csrf_token' => csrf_token(),
    ]);
});
        // Login API real
        Route::post('login', [AuthApiController::class, 'login']);
        // Rutas de recibos protegidas por auth
        Route::middleware('auth')->group(function () {
            Route::get('recibos/nomina', [ReciboApiController::class, 'nomina']);
            Route::get('recibos/vacaciones', [ReciboApiController::class, 'vacaciones']);
        });
    });

Route::post('logout', [LogoutController::class, '__invoke'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [UserController::class, 'update'])->name('profile.update');
    
    // Rutas de recibos (disponibles para todos los usuarios autenticados)
    Route::get('recibos', [ReciboController::class, 'index'])->name('recibos.index');
    Route::get('recibos/{detalle}', [ReciboController::class, 'show'])->name('recibos.show');
    Route::get('recibos/vacaciones/{pago}', [ReciboController::class, 'showVacaciones'])->name('recibos.vacaciones.show');

    // Rutas de vacaciones (disponibles para todos los usuarios autenticados)
    Route::get('vacaciones/disfrute', [VacacionController::class, 'disfruteResumen'])
        ->name('vacaciones.disfrute');
    Route::get('vacaciones/por-pagar', [VacacionController::class, 'porPagar'])
        ->name('vacaciones.por_pagar');
    Route::resource('vacaciones', VacacionController::class);
    Route::post('vacaciones/{vacacion}/aprobar', [VacacionController::class, 'aprobar'])->name('vacaciones.aprobar');
    Route::post('vacaciones/{vacacion}/rechazar', [VacacionController::class, 'rechazar'])->name('vacaciones.rechazar');
    Route::post('vacaciones/pagar/{empleado}', [VacacionController::class, 'pagarPendiente'])->name('vacaciones.pagar_pendiente');
    
    // Rutas de administrador
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        
        // Rutas de gestión de empleados
        Route::post('empleados/import', [App\Http\Controllers\EmpleadoController::class, 'import'])->name('empleados.import');
        // Ruta específica antes del resource para evitar conflicto con /empleados/{empleado}
        Route::get('empleados/antiguedad-pendiente', [EmpleadoController::class, 'antiguedadPendiente'])->name('empleados.antiguedad.pendiente');
        Route::post('empleados/{empleado}/actualizar-antiguedad', [EmpleadoController::class, 'actualizarAntiguedad'])->name('empleados.actualizar.antiguedad');
        Route::resource('empleados', EmpleadoController::class);
        Route::get('empleados/beneficios-por-cargo/{cargo}', [EmpleadoController::class, 'beneficiosPorCargo'])->name('empleados.beneficios-por-cargo');
        Route::get('empleados-import', [EmpleadoController::class, 'importForm'])->name('empleados.import.form');
        Route::resource('cargos', CargoController::class)->except(['show']);
        Route::resource('departamentos', DepartamentoController::class)->except(['show']);
        Route::resource('horarios', HorarioController::class)->except(['show']);
        Route::resource('estados', EstadoController::class)->except(['show']);
        
        // Rutas para primas y remuneraciones
        Route::resource('prima-antiguedad', PrimaAntiguedadController::class)->except(['show']);
        Route::resource('prima-hijo', PrimaHijoController::class)->except(['show']);
        Route::resource('prima-profesionalizacion', PrimaProfesionalizacionController::class)->except(['show']);
        Route::resource('niveles-rangos', NivelRangoController::class)->except(['show']);
        Route::resource('grupos-cargos', GrupoCargoController::class)->except(['show']);
        Route::resource('remuneraciones', RemuneracionController::class)->except(['show']);
        Route::get('remuneraciones-import', [RemuneracionController::class, 'importForm'])->name('remuneraciones.import.form');
        Route::post('remuneraciones-import', [RemuneracionController::class, 'import'])->name('remuneraciones.import');
        Route::get('remuneraciones-template', [RemuneracionController::class, 'downloadTemplate'])->name('remuneraciones.template');
        Route::resource('deducciones', DeduccionController::class)->except(['show']);
        
        // Rutas para noticias
        Route::resource('noticias', NoticiaController::class);
        
        // Rutas para nóminas
        Route::resource('nominas', NominaController::class);
    Route::get('nominas/{nomina}/descargar-recibos', [NominaController::class, 'descargarRecibos'])->name('nominas.descargar.recibos');
        Route::get('nominas/{nomina}/generate', [NominaController::class, 'generate'])->name('nominas.generate');
        Route::post('nominas/{nomina}/change-status', [NominaController::class, 'changeStatus'])->name('nominas.changeStatus');
        Route::get('nominas/{nomina}/export-pdf', [NominaController::class, 'exportPdf'])->name('nominas.exportPdf');
        
        // Rutas para configuración de nóminas
        Route::resource('deduction-configs', DeductionConfigController::class);
        Route::resource('benefit-configs', BenefitConfigController::class);
        Route::get('beneficios-cargo', [BeneficioCargoController::class, 'index'])->name('beneficios-cargo.index');
        Route::get('beneficios-cargo/create', [BeneficioCargoController::class, 'create'])->name('beneficios-cargo.create');
        Route::post('beneficios-cargo', [BeneficioCargoController::class, 'store'])->name('beneficios-cargo.store');
        Route::get('beneficios-cargo/{beneficios_cargo}/edit', [BeneficioCargoController::class, 'edit'])->name('beneficios-cargo.edit');
        Route::put('beneficios-cargo/{beneficios_cargo}', [BeneficioCargoController::class, 'update'])->name('beneficios-cargo.update');
        Route::delete('beneficios-cargo/{beneficios_cargo}', [BeneficioCargoController::class, 'destroy'])->name('beneficios-cargo.destroy');

        Route::post('beneficios', [BeneficioCargoController::class, 'storeBeneficio'])->name('beneficios.store');
        Route::get('beneficios/{beneficio}/edit', [BeneficioCargoController::class, 'editBeneficio'])->name('beneficios.edit');
        Route::put('beneficios/{beneficio}', [BeneficioCargoController::class, 'updateBeneficio'])->name('beneficios.update');
        Route::delete('beneficios/{beneficio}', [BeneficioCargoController::class, 'destroyBeneficio'])->name('beneficios.destroy');
        
        // Ruta de prueba para solucionar el problema de los grupos de cargo
        Route::get('test-grupos', function() {
            return view('empleados.temp-test');
        });
        
        // Rutas AJAX para empleados
        Route::get('grupos-por-tipo/{tipo}', [GrupoCargoController::class, 'getGruposPorTipo'])->name('grupos.por.tipo');
        Route::get('remuneracion-por-grupo/{grupoId}', [RemuneracionController::class, 'getRemuneracionPorGrupo'])->name('remuneracion.por.grupo');
        // Ruta AJAX general para obtener remuneración por parámetros (incluye obreros)
        Route::get('remuneracion', [RemuneracionApiController::class, 'obtener'])->name('remuneracion.obtener');
    });
    
});

