<?php

namespace App\Services;

use App\Models\UserInteraction;
use App\Models\Task;
use App\Models\TaskFeature;
use App\Models\User;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Clusterers\KMeans;
use Rubix\ML\Transformers\Normalizer;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TaskRecommenderService
{
    private $model;
    private $normalizer;
    private $modelPath;

    public function __construct()
    {
        $this->modelPath = storage_path('app/ml_models/task_recommender.model');
        $this->loadOrTrainModel();
    }

    public function recordInteraction($userId, $taskId, $interactionType, $duration = null)
    {
        UserInteraction::create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'interaction_type' => $interactionType,
            'duration' => $duration,
            'timestamp' => now(),
        ]);

        // Recalcular recomendaciones después de un tiempo
        Cache::forget("user_recommendations_{$userId}");
    }

    public function getRecommendations($userId, $limit = 5)
    {
        return Cache::remember("user_recommendations_{$userId}", 3600, function () use ($userId, $limit) {
            return $this->generateRecommendations($userId, $limit);
        });
    }

    private function generateRecommendations($userId, $limit)
    {
        // Obtener historial del usuario
        $userHistory = UserInteraction::where('user_id', $userId)
            ->with('task')
            ->orderBy('timestamp', 'desc')
            ->limit(50)
            ->get();

        if ($userHistory->isEmpty()) {
            return $this->getPopularTasks($limit);
        }

        // Obtener tareas completadas recientemente
        $completedTasks = $userHistory
            ->where('interaction_type', 'complete')
            ->pluck('task_id')
            ->unique()
            ->toArray();

        if (empty($completedTasks)) {
            return $this->getSimilarTasksBasedOnViews($userHistory, $limit);
        }

        return $this->getContentBasedRecommendations($completedTasks, $userId, $limit);
    }

    private function getPopularTasks($limit)
    {
        return Task::where('status', '!=', 'completed')
            ->withCount(['userInteractions as interactions_count' => function ($query) {
                $query->where('interaction_type', 'view');
            }])
            ->orderBy('interactions_count', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getSimilarTasksBasedOnViews($userHistory, $limit)
    {
        $viewedTasks = $userHistory
            ->where('interaction_type', 'view')
            ->pluck('task_id')
            ->unique()
            ->toArray();

        if (empty($viewedTasks)) {
            return collect();
        }

        // Buscar tareas similares basadas en categorías
        $viewedCategories = Task::whereIn('id', array_slice($viewedTasks, 0, 3))
            ->pluck('category')
            ->filter()
            ->unique()
            ->toArray();

        return Task::where('status', '!=', 'completed')
            ->whereIn('category', $viewedCategories)
            ->whereNotIn('id', $viewedTasks)
            ->limit($limit)
            ->get();
    }

    private function getContentBasedRecommendations($completedTasks, $userId, $limit)
    {
        // Obtener características de las tareas completadas
        $completedTaskFeatures = TaskFeature::whereIn('task_id', array_slice($completedTasks, 0, 3))
            ->get();

        if ($completedTaskFeatures->isEmpty()) {
            return $this->getPopularTasks($limit);
        }

        // Calcular preferencias del usuario
        $userPreferences = $this->calculateUserPreferences($userId);

        // Buscar tareas similares
        $recommendedTasks = Task::where('status', '!=', 'completed')
            ->whereNotIn('id', $completedTasks)
            ->with('taskFeature')
            ->get()
            ->map(function ($task) use ($completedTaskFeatures, $userPreferences) {
                $similarityScore = $this->calculateTaskSimilarity($task, $completedTaskFeatures, $userPreferences);
                return [
                    'task' => $task,
                    'score' => $similarityScore
                ];
            })
            ->sortByDesc('score')
            ->take($limit)
            ->pluck('task');

        return $recommendedTasks;
    }

    private function calculateUserPreferences($userId)
    {
        $interactions = UserInteraction::where('user_id', $userId)
            ->with('task.taskFeature')
            ->get();

        $preferences = [
            'categories' => [],
            'difficulties' => [],
            'completion_rate' => 0
        ];

        $totalInteractions = $interactions->count();
        $completedTasks = $interactions->where('interaction_type', 'complete')->count();

        if ($totalInteractions > 0) {
            $preferences['completion_rate'] = $completedTasks / $totalInteractions;

            // Calcular preferencias de categoría
            $categoryCounts = $interactions->groupBy('task.category')->map->count();
            $preferences['categories'] = $categoryCounts->sortDesc()->take(3)->keys()->toArray();

            // Calcular preferencias de dificultad
            $difficultyCounts = $interactions
                ->filter(fn($interaction) => $interaction->task->taskFeature)
                ->groupBy('task.taskFeature.difficulty')
                ->map->count();
            
            $preferences['difficulties'] = $difficultyCounts->sortDesc()->take(2)->keys()->toArray();
        }

        return $preferences;
    }

    private function calculateTaskSimilarity($task, $completedTaskFeatures, $userPreferences)
    {
        $score = 0;
        $taskFeature = $task->taskFeature;

        if (!$taskFeature) {
            return $score;
        }

        // Similitud basada en categoría
        if (in_array($task->category, $userPreferences['categories'])) {
            $score += 3;
        }

        // Similitud basada en dificultad
        if (in_array($taskFeature->difficulty, $userPreferences['difficulties'])) {
            $score += 2;
        }

        // Similitud con tareas completadas recientemente
        foreach ($completedTaskFeatures as $completedFeature) {
            if ($task->category === $completedFeature->task->category) {
                $score += 2;
            }
            if ($taskFeature->difficulty === $completedFeature->difficulty) {
                $score += 1;
            }
            if ($taskFeature->priority === $completedFeature->priority) {
                $score += 1;
            }
        }

        return $score;
    }

    private function loadOrTrainModel()
    {
        // Implementación básica - puedes expandir con Rubix ML más adelante
        // Por ahora usamos un enfoque basado en reglas
    }
}