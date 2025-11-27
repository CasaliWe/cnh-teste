<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;


// Registrar um usuário
Route::post('/registro', [AuthController::class, 'register'])->middleware('throttle:3,1')->name('api.register');