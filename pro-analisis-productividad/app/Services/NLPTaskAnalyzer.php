<?php
// app/Services/NLPTaskAnalyzer.php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Task;

class NLPTaskAnalyzer
{
    private $complexityKeywords = [
        'alta' => ['complejo', 'difícil', 'desafío', 'crítico', 'urgente', 'prioridad'],
        'media' => ['moderado', 'medio', 'regular', 'estándar'],
        'baja' => ['sencillo', 'fácil', 'rápido', 'simple', 'básico']
    ];

    private $categoryKeywords = [
        'desarrollo' => ['código', 'programar', 'software', 'aplicación', 'web', 'mobile'],
        'diseño' => ['diseñar', 'ui', 'ux', 'interfaz', 'prototipo', 'mockup'],
        'análisis' => ['analizar', 'investigar', 'estudiar', 'evaluar', 'reporte'],
        'reunión' => ['reunión', 'meeting', 'llamada', 'coordinación'],
        'documentación' => ['documentar', 'manual', 'guía', 'tutorial', 'especificación']
    ];

    public function analyzeTaskComplexity($title, $description = '')
    {
        $text = strtolower($title . ' ' . $description);
        
        $scores = [
            'alta' => $this->countKeywords($text, $this->complexityKeywords['alta']),
            'media' => $this->countKeywords($text, $this->complexityKeywords['media']),
            'baja' => $this->countKeywords($text, $this->complexityKeywords['baja'])
        ];

        return array_search(max($scores), $scores);
    }

    public function predictTaskCategory($title, $description = '')
    {
        $text = strtolower($title . ' ' . $description);
        
        $scores = [];
        foreach ($this->categoryKeywords as $category => $keywords) {
            $scores[$category] = $this->countKeywords($text, $keywords);
        }

        $predictedCategory = array_search(max($scores), $scores);
        return $scores[$predictedCategory] > 0 ? $predictedCategory : 'general';
    }

    public function estimateTaskDuration($title, $description = '')
    {
        $complexity = $this->analyzeTaskComplexity($title, $description);
        $wordCount = str_word_count($title . ' ' . $description);
        
        $baseDurations = [
            'baja' => 30,   // 30 minutos
            'media' => 120, // 2 horas
            'alta' => 240   // 4 horas
        ];

        $duration = $baseDurations[$complexity] ?? 60;
        
        // Ajustar por longitud del texto
        if ($wordCount > 100) {
            $duration *= 1.5;
        }

        return round($duration / 30) * 30; // Redondear a múltiplos de 30 minutos
    }

    private function countKeywords($text, $keywords)
    {
        $count = 0;
        foreach ($keywords as $keyword) {
            $count += substr_count($text, $keyword);
        }
        return $count;
    }

    public function findSimilarTasks($taskId, $limit = 5)
    {
        $currentTask = Task::find($taskId);
        if (!$currentTask) return collect();

        $allTasks = Task::where('id', '!=', $taskId)
            ->where('user_id', $currentTask->user_id)
            ->get();

        $similarTasks = [];
        foreach ($allTasks as $task) {
            $similarity = $this->calculateTaskSimilarity($currentTask, $task);
            if ($similarity > 0.3) { // 30% de similitud mínima
                $task->similarity_score = $similarity * 100;
                $similarTasks[] = $task;
            }
        }

        return collect($similarTasks)
            ->sortByDesc('similarity_score')
            ->take($limit);
    }

    private function calculateTaskSimilarity($task1, $task2)
    {
        $text1 = strtolower($task1->title . ' ' . $task1->description);
        $text2 = strtolower($task2->title . ' ' . $task2->description);

        // Similitud por palabras comunes
        $words1 = array_unique(str_word_count($text1, 1));
        $words2 = array_unique(str_word_count($text2, 1));
        
        $commonWords = array_intersect($words1, $words2);
        $totalWords = count(array_unique(array_merge($words1, $words2)));

        $textSimilarity = $totalWords > 0 ? count($commonWords) / $totalWords : 0;

        // Similitud por categoría y prioridad
        $metaSimilarity = 0;
        if ($task1->category === $task2->category) $metaSimilarity += 0.3;
        if ($task1->priority === $task2->priority) $metaSimilarity += 0.2;

        return ($textSimilarity * 0.6) + ($metaSimilarity * 0.4);
    }
}