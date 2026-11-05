<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpedienteController;

Route::get('/', [ExpedienteController::class, 'index']);
Route::get('/expedientes/data', [ExpedienteController::class, 'data']);