<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Rota pública - formulário de login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


// Rotas privadas (protegidas pelo middleware auth)
Route::middleware('auth')->group(function () {
    Route::get('/',         [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/perfil',   [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
});
