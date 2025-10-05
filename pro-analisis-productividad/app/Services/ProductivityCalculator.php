<?php

namespace App\Services;

use App\Models\TimeTracking;
use App\Models\Project;
use Carbon\Carbon;

class ProductivityCalculator
{
    /**
     * Calcula métricas de productividad para un usuario y período
     */
    public function calculateUserProductivity($userId, $startDate = null, $endDate = null)
    {
        // Si no se especifican fechas, usar el último mes
        if (!$startDate) {
            $startDate = Carbon::now()->subMonth();
        }
        if (!$endDate) {
            $endDate = Carbon::now();
        }

        // Obtener registros de tiempo del usuario
        $timeEntries = TimeTracking::where('user_id', $userId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->get();

        return $this->calculateMetrics($timeEntries, $startDate, $endDate);
    }

    /**
     * Calcula métricas para un proyecto específico
     */
    public function calculateProjectProductivity($projectId, $startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subMonth();
        }
        if (!$endDate) {
            $endDate = Carbon::now();
        }

        $timeEntries = TimeTracking::where('project_id', $projectId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->get();

        return $this->calculateMetrics($timeEntries, $startDate, $endDate);
    }

    /**
     * Calcula todas las métricas basadas en los registros de tiempo
     */
    private function calculateMetrics($timeEntries, $startDate, $endDate)
    {
        if ($timeEntries->isEmpty()) {
            return $this->getEmptyMetrics();
        }

        $totalTime = $this->calculateTotalTime($timeEntries);
        $productiveTime = $this->calculateProductiveTime($timeEntries);
        $daysInPeriod = $startDate->diffInDays($endDate) ?: 1;

        return [
            'total_time_minutes' => $totalTime,
            'total_time_hours' => round($totalTime / 60, 2),
            'productive_time_minutes' => $productiveTime,
            'productive_time_hours' => round($productiveTime / 60, 2),
            'efficiency_score' => $this->calculateEfficiencyScore($totalTime, $productiveTime),
            'focus_ratio' => $this->calculateFocusRatio($timeEntries),
            'consistency_score' => $this->calculateConsistencyScore($timeEntries, $daysInPeriod),
            'daily_average_minutes' => round($totalTime / $daysInPeriod, 2),
            'tasks_completed' => $this->countCompletedTasks($timeEntries),
            'completion_rate' => $this->calculateCompletionRate($timeEntries),
            'peak_hours' => $this->findPeakProductivityHours($timeEntries),
            'productivity_trend' => $this->calculateProductivityTrend($timeEntries, $startDate, $endDate),
        ];
    }

    /**
     * Calcula el tiempo total en minutos
     */
    private function calculateTotalTime($timeEntries)
    {
        return $timeEntries->sum(function ($entry) {
            if ($entry->end_time) {
                return $entry->start_time->diffInMinutes($entry->end_time);
            }
            return 0;
        });
    }

    /**
     * Calcula tiempo productivo (asumiendo que todos los registros son productivos por ahora)
     */
    private function calculateProductiveTime($timeEntries)
    {
        // Por defecto, todo el tiempo registrado se considera productivo
        // Puedes modificar esta lógica según tus categorías
        return $this->calculateTotalTime($timeEntries);
    }

    /**
     * Calcula puntuación de eficiencia (0-100)
     */
    private function calculateEfficiencyScore($totalTime, $productiveTime)
    {
        if ($totalTime === 0) return 0;
        
        $efficiency = ($productiveTime / $totalTime) * 100;
        return min(round($efficiency, 2), 100);
    }

    /**
     * Calcula ratio de concentración
     */
    private function calculateFocusRatio($timeEntries)
    {
        $totalSessions = $timeEntries->count();
        if ($totalSessions === 0) return 0;

        $focusedSessions = $timeEntries->filter(function ($entry) {
            // Sesiones de más de 25 minutos se consideran enfocadas
            if ($entry->end_time) {
                $duration = $entry->start_time->diffInMinutes($entry->end_time);
                return $duration >= 25;
            }
            return false;
        })->count();

        return round(($focusedSessions / $totalSessions) * 100, 2);
    }

    /**
     * Calcula puntuación de consistencia
     */
    private function calculateConsistencyScore($timeEntries, $daysInPeriod)
    {
        $daysWithWork = $timeEntries->groupBy(function ($entry) {
            return $entry->start_time->format('Y-m-d');
        })->count();

        return round(($daysWithWork / $daysInPeriod) * 100, 2);
    }

    /**
     * Cuenta tareas completadas
     */
    private function countCompletedTasks($timeEntries)
    {
        return $timeEntries->where('completed', true)->count();
    }

    /**
     * Calcula tasa de completitud
     */
    private function calculateCompletionRate($timeEntries)
    {
        $totalTasks = $timeEntries->count();
        if ($totalTasks === 0) return 0;

        $completedTasks = $this->countCompletedTasks($timeEntries);
        return round(($completedTasks / $totalTasks) * 100, 2);
    }

    /**
     * Encuentra horas pico de productividad
     */
    private function findPeakProductivityHours($timeEntries)
    {
        $hours = $timeEntries->groupBy(function ($entry) {
            return $entry->start_time->hour;
        })->map(function ($entries) {
            return $entries->sum(function ($entry) {
                if ($entry->end_time) {
                    return $entry->start_time->diffInMinutes($entry->end_time);
                }
                return 0;
            });
        });

        if ($hours->isEmpty()) {
            return [];
        }

        $maxProductivity = $hours->max();
        $peakHours = $hours->filter(function ($minutes) use ($maxProductivity) {
            return $minutes >= ($maxProductivity * 0.8); // 80% del máximo o más
        })->keys()->sort()->values()->toArray();

        return $peakHours;
    }

    /**
     * Calcula tendencia de productividad
     */
    private function calculateProductivityTrend($timeEntries, $startDate, $endDate)
    {
        $weeklyData = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $weekStart = $current->copy();
            $weekEnd = $current->copy()->addDays(6);
            
            $weekEntries = $timeEntries->filter(function ($entry) use ($weekStart, $weekEnd) {
                return $entry->start_time->between($weekStart, $weekEnd);
            });

            $weeklyData[] = [
                'week' => $weekStart->format('Y-m-d'),
                'minutes' => $this->calculateTotalTime($weekEntries),
                'efficiency' => $this->calculateEfficiencyScore(
                    $this->calculateTotalTime($weekEntries),
                    $this->calculateProductiveTime($weekEntries)
                )
            ];

            $current->addWeek();
        }

        return $weeklyData;
    }

    /**
     * Métricas vacías para cuando no hay datos
     */
    private function getEmptyMetrics()
    {
        return [
            'total_time_minutes' => 0,
            'total_time_hours' => 0,
            'productive_time_minutes' => 0,
            'productive_time_hours' => 0,
            'efficiency_score' => 0,
            'focus_ratio' => 0,
            'consistency_score' => 0,
            'daily_average_minutes' => 0,
            'tasks_completed' => 0,
            'completion_rate' => 0,
            'peak_hours' => [],
            'productivity_trend' => [],
        ];
    }

    /**
     * Genera recomendaciones basadas en las métricas
     */
    public function generateRecommendations($metrics)
    {
        $recommendations = [];

        if ($metrics['efficiency_score'] < 60) {
            $recommendations[] = "Tu eficiencia es baja. Considera eliminar distracciones durante el trabajo.";
        }

        if ($metrics['focus_ratio'] < 50) {
            $recommendations[] = "Intenta trabajar en sesiones más largas y enfocadas (mínimo 25 minutos).";
        }

        if ($metrics['consistency_score'] < 70) {
            $recommendations[] = "Trabaja en establecer una rutina más consistente.";
        }

        if (!empty($metrics['peak_hours'])) {
            $recommendations[] = "Tus horas más productivas son: " . 
                implode(', ', array_map(function($h) { 
                    return $h . ':00'; 
                }, $metrics['peak_hours'])) . 
                ". Programa tareas importantes en estos horarios.";
        }

        if (empty($recommendations)) {
            $recommendations[] = "¡Buen trabajo! Tus métricas de productividad se ven excelentes.";
        }

        return $recommendations;
    }
}