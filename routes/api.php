<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RemuneracionApiController;
use App\Http\Controllers\GrupoCargoApiController;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\ReciboApiController;

Route::get('remuneracion/obtener', [RemuneracionApiController::class, 'obtener']);
Route::get('remuneracion/por-grupo/{grupo_cargo_id}', [RemuneracionApiController::class, 'obtenerPorGrupo']);
Route::get('grupos-cargo/por-tipo/{tipo_cargo}', [GrupoCargoApiController::class, 'obtenerPorTipo']);
Route::get('grupos-por-tipo', [GrupoCargoApiController::class, 'getGruposPorTipo']);

Route::get('test-api-route', function () {
    return ['message' => 'API test route is working'];
});

Route::post('login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('recibos/nomina/{id}', [ReciboApiController::class, 'recibo']);
    Route::get('recibos/nomina', [ReciboApiController::class, 'nomina']);
    Route::get('recibos/vacaciones', [ReciboApiController::class, 'vacaciones']);
    //Route::get('recibos', [ReciboApiController::class, 'index']);
    //Route::get('recibos/{id}', [ReciboApiController::class, 'show']);
    //Route::post('recibos', [ReciboApiController::class, 'store']);
    //Route::put('recibos/{id}', [ReciboApiController::class, 'update']);
    //Route::delete('recibos/{id}', [ReciboApiController::class, 'destroy']);
    
});
