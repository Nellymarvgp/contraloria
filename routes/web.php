<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\PrimaAntiguedadController;
use App\Http\Controllers\PrimaProfesionalizacionController;
use App\Http\Controllers\NivelRangoController;
use App\Http\Controllers\GrupoCargoController;
use App\Http\Controllers\RemuneracionController;
use App\Http\Controllers\DeduccionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\NominaController;
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

Route::post('logout', [LogoutController::class, '__invoke'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [UserController::class, 'update'])->name('profile.update');
    
    // Rutas de administrador
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        
        // Rutas de gestión de empleados
        Route::resource('empleados', EmpleadoController::class)->except(['show']);
        Route::resource('cargos', CargoController::class)->except(['show']);
        Route::resource('departamentos', DepartamentoController::class)->except(['show']);
        Route::resource('horarios', HorarioController::class)->except(['show']);
        Route::resource('estados', EstadoController::class)->except(['show']);
        
        // Rutas para primas y remuneraciones
        Route::resource('prima-antiguedad', PrimaAntiguedadController::class)->except(['show']);
        Route::resource('prima-profesionalizacion', PrimaProfesionalizacionController::class)->except(['show']);
        Route::resource('niveles-rangos', NivelRangoController::class)->except(['show']);
        Route::resource('grupos-cargos', GrupoCargoController::class)->except(['show']);
        Route::resource('remuneraciones', RemuneracionController::class)->except(['show']);
        Route::resource('deducciones', DeduccionController::class)->except(['show']);
        
        // Rutas para noticias
        Route::resource('noticias', NoticiaController::class);
        
        // Rutas para nóminas
        Route::resource('nominas', NominaController::class);
        Route::get('nominas/{nomina}/generate', [NominaController::class, 'generate'])->name('nominas.generate');
        Route::post('nominas/{nomina}/change-status', [NominaController::class, 'changeStatus'])->name('nominas.changeStatus');
        Route::get('nominas/{nomina}/export-pdf', [NominaController::class, 'exportPdf'])->name('nominas.exportPdf');
    });
});
