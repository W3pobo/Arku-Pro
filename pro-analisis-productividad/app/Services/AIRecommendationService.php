<?php
// app/Services/AIRecommendationService.php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Models\UserInteraction;
use Illuminate\Support\Collection;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Kernels\Distance\Euclidean;
use Rubix\ML\Transformers\NumericStringConverter;
use Rubix\ML\Transformers\OneHotEncoder;
use Rubix\ML\Transformers\ZScaleStandardizer;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;

class AIRecommendationService
{
    private $model;
    private $isTrained = false;

    public function __construct()
    {
        // Inicializar modelo KNN (K-Nearest Neighbors)
        $this->model = new KNearestNeighbors(5, true, new Euclidean());
    }

    public function getAIRecommendations($userId, $limit = 5)
    {
        // Entrenar modelo con datos históricos
        $this->trainModel($userId);

        // Obtener características del usuario
        $userFeatures = $this->getUserFeatures($userId);

        // Predecir tareas recomendadas
        return $this->predictRecommendedTasks($userFeatures, $userId, $limit);
    }

    private function trainModel($userId)
    {
        // Obtener datos de entrenamiento (interacciones históricas)
        $trainingData = $this->getTrainingData($userId);

        if ($trainingData->count() < 10) {
            $this->isTrained = false;
            return;
        }

        // Preparar dataset para ML
        $samples = [];
        $labels = [];

        foreach ($trainingData as $interaction) {
            $samples[] = $this->extractFeatures($interaction);
            $labels[] = $interaction->will_complete ? 'recommend' : 'not_recommend';
        }

        $dataset = new Labeled($samples, $labels);

        // Aplicar transformaciones
        $transformers = [
            new NumericStringConverter(),
            new OneHotEncoder(),
            new ZScaleStandardizer()
        ];

        foreach ($transformers as $transformer) {
            $dataset->apply($transformer);
        }

        // Entrenar modelo
        $this->model->train($dataset);
        $this->isTrained = true;
    }

    private function getTrainingData($userId)
    {
        return UserInteraction::where('user_id', $userId)
            ->with('task')
            ->get()
            ->map(function($interaction) {
                $interaction->will_complete = $interaction->interaction_type === 'complete';
                return $interaction;
            });
    }

    private function extractFeatures($interaction)
    {
        $task = $interaction->task;
        
        return [
            'hour_of_day' => $interaction->created_at->hour,
            'day_of_week' => $interaction->created_at->dayOfWeek,
            'task_priority' => $this->priorityToNumber($task->priority),
            'task_category' => $task->category,
            'estimated_duration' => $task->taskFeature->estimated_duration ?? 30,
            'has_description' => !empty($task->description) ? 1 : 0,
            'user_productivity' => $this->getUserProductivityAtTime($interaction->user_id, $interaction->created_at),
            'similar_tasks_completed' => $this->getSimilarTasksCompleted($interaction->user_id, $task),
            'completion_rate_category' => $this->getCategoryCompletionRate($interaction->user_id, $task->category)
        ];
    }

    private function predictRecommendedTasks($userFeatures, $userId, $limit)
    {
        if (!$this->isTrained) {
            return $this->getFallbackRecommendations($userId, $limit);
        }

        $candidateTasks = $this->getCandidateTasks($userId);
        $recommendations = [];

        foreach ($candidateTasks as $task) {
            $features = array_merge($userFeatures, $this->extractTaskFeatures($task));
            
            $dataset = new Unlabeled([$features]);
            $prediction = $this->model->predict($dataset);
            
            if ($prediction[0] === 'recommend') {
                $probability = $this->model->proba($dataset)[0]['recommend'] ?? 0.5;
                $task->ai_confidence = $probability * 100;
                $recommendations[] = $task;
            }
        }

        return collect($recommendations)
            ->sortByDesc('ai_confidence')
            ->take($limit);
    }

    private function getUserFeatures($userId)
    {
        $user = User::find($userId);
        
        return [
            'current_hour' => now()->hour,
            'current_day' => now()->dayOfWeek,
            'avg_daily_tasks' => $this->getAverageDailyTasks($userId),
            'preferred_work_hours' => $this->getPreferredWorkHours($userId),
            'productivity_trend' => $this->getProductivityTrend($userId),
            'fatigue_level' => $this->calculateFatigueLevel($userId)
        ];
    }

    private function getPreferredWorkHours($userId)
    {
        return UserInteraction::where('user_id', $userId)
            ->where('interaction_type', 'complete')
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as completions')
            ->groupBy('hour')
            ->orderByDesc('completions')
            ->first()
            ->hour ?? 14; // 2 PM por defecto
    }

    private function calculateFatigueLevel($userId)
    {
        $recentCompletions = UserInteraction::where('user_id', $userId)
            ->where('interaction_type', 'complete')
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        return min(1.0, $recentCompletions / 10); // 0-1 scale
    }
}