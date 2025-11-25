<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeTrackingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\ProductivityTagController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\TaskController;
// AGREGADO: Importamos el controlador de IA
use App\Http\Controllers\AIRecommendationController;

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

    // Autenticación con Google
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

    // Restablecimiento de contraseña
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Verificación de email
    Route::get('/email/verify', [VerificationController::class, 'show'])
        ->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->name('verification.verify');
    
    Route::post('/email/resend', [VerificationController::class, 'resend'])
        ->name('verification.resend');

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Proyectos
    Route::resource('projects', ProjectController::class);
    
    // Registro de tiempo
    Route::resource('time-trackings', TimeTrackingController::class);
    
    // Reportes
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/productivity', [ReportController::class, 'productivity'])->name('reports.productivity');
        Route::get('/project/{project}', [ReportController::class, 'projectReport'])->name('reports.project');
    });

    // Perfil de usuario
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Categorías de actividad y tags de productividad
    Route::resource('activity-categories', ActivityCategoryController::class);
    Route::resource('productivity-tags', ProductivityTagController::class);

    // Tareas
    Route::resource('tasks', TaskController::class);
    Route::post('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    
    // ---------------------------------------------------------------------
    // RUTAS DE RECOMENDACIONES E IA (MOVIDAS DESDE API.PHP)
    // ---------------------------------------------------------------------
    // Nota: Mantenemos el prefijo '/api/' en la URL para que el JavaScript
    // del dashboard siga funcionando sin cambios, pero ahora usan sesión web.
    
    // Página de recomendaciones
    Route::get('/recommendations', [RecommendationController::class, 'showRecommendationsPage'])
        ->name('recommendations');
    
    // Endpoints consumidos por AJAX (Fetch) en el Dashboard
    Route::get('/api/recommendations', [RecommendationController::class, 'getRecommendations'])
        ->name('api.recommendations');
    
    Route::post('/api/record-interaction', [RecommendationController::class, 'recordInteraction'])
        ->name('api.record_interaction');

    // Endpoints de Inteligencia Artificial (IA)
    Route::get('/api/ai/analysis', [AIRecommendationController::class, 'getAIAnalysis'])
        ->name('api.ai.analysis');
        
    Route::get('/api/ai/insights', [AIRecommendationController::class, 'getAIInsights'])
        ->name('api.ai.insights');
        
    Route::post('/api/ai/interaction', [AIRecommendationController::class, 'recordAIInteraction'])
        ->name('api.ai.interaction');
        
    Route::get('/api/ai/recommendations', [AIRecommendationController::class, 'getAIRecommendations'])
        ->name('api.ai.recommendations');

    // Cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Ruta de fallback
Route::fallback(function () {
    return redirect()->route('home');
});