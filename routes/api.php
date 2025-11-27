<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Registrar um usuário
Route::post('/registro', [AuthController::class, 'register'])->name('api.register');