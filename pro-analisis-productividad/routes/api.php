<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductivityDataController;

Route::middleware('auth:sanctum')->group(function () {
    // Datos para análisis de productividad
    Route::get('/user/{user}/productivity-data', [ProductivityDataController::class, 'getUserProductivityData']);
    Route::get('/user/{user}/time-trackings', [ProductivityDataController::class, 'getTimeTrackings']);
    Route::get('/user/{user}/projects', [ProductivityDataController::class, 'getProjects']);
    
    // Recomendaciones desde Python
    Route::post('/user/{user}/recommendations', [ProductivityDataController::class, 'storeRecommendations']);
});

// Webhook para integraciones externas
Route::post('/webhook/github', [ProductivityDataController::class, 'handleGitHubWebhook']);
Route::post('/webhook/gitlab', [ProductivityDataController::class, 'handleGitLabWebhook']);
Route::post('/webhook/jira', [ProductivityDataController::class, 'handleJiraWebhook']);