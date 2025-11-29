<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\SimuladoController;


// Registrar um usuário
Route::post('/registro', [AuthController::class, 'register'])->middleware('throttle:3,1')->name('api.register');

// Cria um simulado
Route::post('/criar-simulado', [SimuladoController::class, 'criar'])->name('api.create_simulado');