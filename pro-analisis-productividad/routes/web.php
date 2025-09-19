<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeTrackingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\GoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta de inicio (pública)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas públicas de autenticación
Route::middleware('guest')->group(function () {
    // Vistas de autenticación
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

    // Autenticación tradicional
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);


 Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
 Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Dashboard principal - CORREGIDO: Usando el controlador
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Proyectos - AÑADIDO: Esta es la ruta que faltaba
    Route::resource('projects', ProjectController::class);
    
    // Registro de tiempo
    Route::resource('time-trackings', TimeTrackingController::class);
    
    // Reportes
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/productivity', [ReportController::class, 'productivity'])->name('reports.productivity');
        Route::get('/project/{project}', [ReportController::class, 'projectReport'])->name('reports.project');
    });

    // Cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Ruta de fallback
Route::fallback(function () {
    return redirect()->route('home');
});