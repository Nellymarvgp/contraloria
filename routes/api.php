<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RemuneracionApiController;

Route::get('remuneracion/obtener', [RemuneracionApiController::class, 'obtener']);
