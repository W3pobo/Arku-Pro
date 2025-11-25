<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductivityDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí registramos las rutas API que requieren autenticación por Token (Sanctum).
| Las rutas consumidas por el Frontend (Blade) se han movido a web.php
| para aprovechar la sesión de usuario.
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // Estas rutas se mantienen aquí por si el script de Python externo
    // necesita consultar datos crudos usando un token de API.
    
    // Datos para análisis de productividad
    Route::get('/user/{user}/productivity-data', [ProductivityDataController::class, 'getUserProductivityData']);
    Route::get('/user/{user}/time-trackings', [ProductivityDataController::class, 'getTimeTrackings']);
    Route::get('/user/{user}/projects', [ProductivityDataController::class, 'getProjects']);
    
    // Ruta para que el script de Python guarde las recomendaciones
    Route::post('/user/{user}/recommendations', [ProductivityDataController::class, 'storeRecommendations']);
});

// Webhook para integraciones externas (Públicas o con firma secreta)
Route::post('/webhook/github', [ProductivityDataController::class, 'handleGitHubWebhook']);
Route::post('/webhook/gitlab', [ProductivityDataController::class, 'handleGitLabWebhook']);
Route::post('/webhook/jira', [ProductivityDataController::class, 'handleJiraWebhook']);