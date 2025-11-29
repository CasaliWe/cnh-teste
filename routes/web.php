<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;


// Rota pública - formulário de login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/esqueci-senha', [AuthController::class, 'showForgotPasswordForm'])->name('esqueci-senha');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1'); // máximo 5 tentativas por minuto

Route::post('/esqueci-senha', [AuthController::class, 'forgotPassword'])
    ->middleware('throttle:3,1') // máximo 3 tentativas por minuto
    ->name('forgot-password');

Route::get('/auth/redirect/google', [AuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/callback/google', [AuthController::class, 'callback']);


// Rotas privadas (protegidas pelo middleware auth)
Route::middleware('auth')->group(function () {
    Route::get('/',         [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/start',    [DashboardController::class, 'start'])->name('start');
    Route::get('/perfil',   [ProfileController::class, 'profile'])->name('profile');
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
});
