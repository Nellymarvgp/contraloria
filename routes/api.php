<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RemuneracionApiController;
use App\Http\Controllers\GrupoCargoApiController;

Route::get('remuneracion/obtener', [RemuneracionApiController::class, 'obtener']);
Route::get('remuneracion/por-grupo/{grupo_cargo_id}', [RemuneracionApiController::class, 'obtenerPorGrupo']);
Route::get('grupos-cargo/por-tipo/{tipo_cargo}', [GrupoCargoApiController::class, 'obtenerPorTipo']);
Route::get('grupos-por-tipo', [GrupoCargoApiController::class, 'getGruposPorTipo']);

Route::get('test-api-route', function () {
    return ['message' => 'API test route is working'];
});
