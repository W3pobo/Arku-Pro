<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\TimeTracking;
use App\Models\Task; // FALTA ESTA IMPORTACIÓN
use Carbon\Carbon;
use App\Services\ProductivityCalculator;

class DashboardController extends Controller
{
    protected $productivityCalculator;

    public function __construct(ProductivityCalculator $productivityCalculator)
    {
        $this->productivityCalculator = $productivityCalculator;
    }

    public function index()
    {
        $user = Auth::user();
        
        // --- 1. DATOS PARA LAS TARJETAS DE ESTADÍSTICAS ---
        $stats = [
            'total_projects' => $user->projects()->count(),
            'active_projects' => $user->projects()->where('status', 'active')->count(),
            'weekly_hours' => $this->getWeeklyHours($user),
            'avg_productivity' => $this->calculateAverageProductivity($user),
            'pending_tasks' => $this->getPendingTasksCount($user),
        ];

        // --- 2. DATOS PARA LA LISTA DE ACTIVIDAD RECIENTE ---
        $recentActivities = $user->timeTrackings()
            ->with(['project', 'activityCategory'])
            ->latest()
            ->take(5)
            ->get();

        // --- 3. DATOS PARA EL GRÁFICO DE ACTIVIDAD SEMANAL (BARRAS) ---
        $weeklyChartData = $this->getWeeklyChartData($user);

        // --- 4. DATOS PARA EL GRÁFICO DE TIPO DE ACTIVIDAD (DONA) ---
        $activityTypeChartData = $this->getActivityTypeChartData($user);

        // --- 5. MÉTRICAS DE PRODUCTIVIDAD AVANZADAS ---
        $productivityMetrics = $this->calculateProductivityMetrics($user);
        
        // --- 6. RECOMENDACIONES DEL SISTEMA ---
        $systemRecommendations = $this->generateBasicRecommendations($user);
        
        // --- 7. ESTADÍSTICAS DE ACTIVIDAD POR CATEGORÍA ---
        $activityStats = $this->getActivityStats($user);
        
        // --- 8. DATOS PARA EL WIDGET DE RESUMEN RÁPIDO ---
        $quickStats = $this->getQuickStats($user, $productivityMetrics);

        // --- 9. TAREAS RECIENTES ---
        $recentTasks = $this->getRecentTasks($user);

        // --- 10. ENVIAR TODOS LOS DATOS A LA VISTA ---
        return view('dashboard', compact(
            'stats', 
            'recentActivities',
            'weeklyChartData',
            'activityTypeChartData',
            'productivityMetrics',
            'systemRecommendations',
            'activityStats',
            'quickStats',
            'recentTasks'
        ));
    }

    /**
     * Calcular horas de la semana actual
     */
    private function getWeeklyHours($user)
    {
        $weeklyMinutes = $user->timeTrackings()
            ->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('duration_minutes');

        return round($weeklyMinutes / 60, 1);
    }

    /**
     * Calcular productividad promedio
     */
    private function calculateAverageProductivity($user)
    {
        $timeTrackings = $user->timeTrackings()
            ->where('start_time', '>=', Carbon::now()->subDays(30))
            ->get();

        if ($timeTrackings->isEmpty()) {
            return 0;
        }

        // Calcular productividad basada en focus_level y energy_level
        $totalProductivity = $timeTrackings->sum(function($tracking) {
            return ($tracking->focus_level + $tracking->energy_level) / 2;
        });

        return round($totalProductivity / $timeTrackings->count(), 1);
    }

    /**
     * Obtener datos para gráfico semanal
     */
    private function getWeeklyChartData($user)
    {
        $weeklyActivity = $user->timeTrackings()
            ->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->selectRaw('DAYOFWEEK(start_time) as day, SUM(duration_minutes) as total_minutes')
            ->groupBy('day')
            ->pluck('total_minutes', 'day')
            ->toArray();

        $weeklyChartDataRaw = array_fill(1, 7, 0);
        foreach ($weeklyActivity as $day => $minutes) {
            $weeklyChartDataRaw[$day] = round($minutes / 60, 1);
        }

        return [
            $weeklyChartDataRaw[2], // Lunes
            $weeklyChartDataRaw[3], // Martes
            $weeklyChartDataRaw[4], // Miércoles
            $weeklyChartDataRaw[5], // Jueves
            $weeklyChartDataRaw[6], // Viernes
            $weeklyChartDataRaw[7], // Sábado
            $weeklyChartDataRaw[1]  // Domingo
        ];
    }

    /**
     * Obtener datos para gráfico por tipo de actividad
     */
    private function getActivityTypeChartData($user)
    {
        $activityTypes = $user->timeTrackings()
            ->where('start_time', '>=', Carbon::now()->subDays(30))
            ->join('activity_categories', 'time_trackings.activity_category_id', '=', 'activity_categories.id')
            ->selectRaw('activity_categories.name as label, SUM(time_trackings.duration_minutes) as total_minutes')
            ->groupBy('activity_categories.name', 'activity_categories.id')
            ->pluck('total_minutes', 'label');

        return [
            'labels' => $activityTypes->keys()->toArray(),
            'data' => $activityTypes->values()->map(function($minutes) {
                return round($minutes / 60, 1);
            })->toArray(),
        ];
    }

    /**
     * Obtener estadísticas de actividad por categoría
     */
    private function getActivityStats($user)
    {
        return TimeTracking::where('time_trackings.user_id', $user->id)
            ->where('start_time', '>=', Carbon::now()->subDays(30))
            ->join('activity_categories', 'time_trackings.activity_category_id', '=', 'activity_categories.id')
            ->selectRaw('
                activity_categories.name as activity_type,
                activity_categories.icon as activity_icon,
                activity_categories.color as activity_color,
                SUM(time_trackings.duration_minutes) as total_minutes,
                AVG((time_trackings.focus_level + time_trackings.energy_level) / 2) as avg_productivity,
                COUNT(*) as total_sessions
            ')
            ->groupBy('activity_categories.name', 'activity_categories.id', 'activity_categories.icon', 'activity_categories.color')
            ->orderBy('total_minutes', 'desc')
            ->get();
    }

    /**
     * Calcular métricas de productividad avanzadas (CORREGIDO)
     */
    private function calculateProductivityMetrics($user)
    {
        $timeTrackings = $user->timeTrackings()
            ->where('start_time', '>=', Carbon::now()->subDays(30))
            ->get();

        if ($timeTrackings->isEmpty()) {
            return [
                'efficiency_score' => 0,
                'focus_ratio' => 0,
                'consistency_score' => 0,
                'total_time_hours' => 0,
                'productive_time_hours' => 0,
                'tasks_completed' => 0,
                'today_productivity' => 0,
                'current_session_minutes' => 0,
            ];
        }

        $totalMinutes = $timeTrackings->sum('duration_minutes');
        
        // CORRECCIÓN: Usar focus_level para determinar tiempo productivo
        // Consideramos productivo cuando focus_level > 60
        $productiveMinutes = $timeTrackings->sum(function($tracking) {
            return ($tracking->focus_level ?? 0) > 60 ? $tracking->duration_minutes : 0;
        });
        
        // Calcular consistencia (días con actividad)
        $daysWithActivity = $timeTrackings->groupBy(function($tracking) {
            return $tracking->start_time->format('Y-m-d');
        })->count();

        // Productividad de hoy
        $todayTimeTrackings = $user->timeTrackings()
            ->whereDate('start_time', Carbon::today())
            ->get();

        $todayMinutes = $todayTimeTrackings->sum('duration_minutes');
        $todayProductiveMinutes = $todayTimeTrackings->sum(function($tracking) {
            return ($tracking->focus_level ?? 0) > 60 ? $tracking->duration_minutes : 0;
        });

        $todayProductivity = $todayMinutes > 0 ? ($todayProductiveMinutes / $todayMinutes) * 100 : 0;

        // Sesión actual (últimas 2 horas)
        $currentSessionMinutes = $user->timeTrackings()
            ->where('start_time', '>=', Carbon::now()->subHours(2))
            ->sum('duration_minutes');

        return [
            'efficiency_score' => $totalMinutes > 0 ? round(($productiveMinutes / $totalMinutes) * 100, 1) : 0,
            'focus_ratio' => round($timeTrackings->avg('focus_level') ?? 0, 1),
            'consistency_score' => round(($daysWithActivity / 30) * 100, 1),
            'total_time_hours' => round($totalMinutes / 60, 1),
            'productive_time_hours' => round($productiveMinutes / 60, 1),
            'tasks_completed' => $timeTrackings->count(),
            'today_productivity' => round($todayProductivity, 1),
            'current_session_minutes' => $currentSessionMinutes,
        ];
    }

    /**
     * Generar recomendaciones básicas
     */
    private function generateBasicRecommendations($user)
    {
        $recommendations = [];
        $projectCount = $user->projects()->count();
        $timeTrackingCount = $user->timeTrackings()->count();
        $taskCount = $user->tasks()->count();

        if ($projectCount === 0) {
            $recommendations[] = "🎯 Crea tu primer proyecto para comenzar a trackear tu tiempo efectivamente.";
            $recommendations[] = "📝 Organiza tus tareas en proyectos para mejor seguimiento.";
        }

        if ($timeTrackingCount === 0) {
            $recommendations[] = "⏱️ Comienza registrando tu tiempo de trabajo para obtener insights personalizados.";
            $recommendations[] = "📊 El sistema aprenderá de tus patrones para darte recomendaciones precisas.";
        }

        if ($taskCount === 0) {
            $recommendations[] = "✅ Crea tu primera tarea para organizar mejor tu trabajo.";
            $recommendations[] = "📋 Las tareas te ayudan a desglosar proyectos en acciones concretas.";
        }

        if ($projectCount > 0 && $timeTrackingCount > 0 && $taskCount > 0) {
            $recommendations[] = "🚀 ¡Buen trabajo! El sistema está analizando tus datos para recomendaciones personalizadas.";
            $recommendations[] = "💡 Completa más sesiones de trabajo para mejorar las recomendaciones del sistema de IA.";
            
            // Análisis de enfoque reciente
            $recentFocus = $user->timeTrackings()
                ->where('created_at', '>=', now()->subDays(3))
                ->avg('focus_level') ?? 0;
                
            if ($recentFocus < 60) {
                $recommendations[] = "⚡ Mejora tu enfoque - intenta eliminar distracciones durante el trabajo.";
            }

            // Análisis de tareas pendientes
            $pendingTasks = $user->tasks()->whereIn('status', ['pending', 'in_progress'])->count();
            if ($pendingTasks > 10) {
                $recommendations[] = "📋 Tienes {$pendingTasks} tareas pendientes - considera priorizar las más importantes.";
            }

            // Análisis de tareas vencidas
            $overdueTasks = $user->tasks()->where('due_date', '<', Carbon::today())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count();
            if ($overdueTasks > 0) {
                $recommendations[] = "⚠️ Tienes {$overdueTasks} tarea(s) vencida(s) - revísalas pronto.";
            }
        }

        // Recomendación basada en consistencia
        $consistency = $user->timeTrackings()
            ->where('start_time', '>=', Carbon::now()->subDays(7))
            ->selectRaw('COUNT(DISTINCT DATE(start_time)) as active_days')
            ->first();

        if ($consistency && $consistency->active_days < 3) {
            $recommendations[] = "📅 Trabaja en establecer una rutina más consistente para mejores resultados.";
        }

        if (empty($recommendations)) {
            $recommendations[] = "🌟 ¡Excelente! Sigue trabajando para obtener recomendaciones más específicas.";
        }

        return array_slice($recommendations, 0, 4); // Máximo 4 recomendaciones
    }

    /**
     * Obtener conteo de tareas pendientes
     */
    private function getPendingTasksCount($user)
    {
        return $user->tasks()
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
    }

    /**
     * Obtener tareas recientes
     */
    private function getRecentTasks($user)
    {
        return $user->tasks()
            ->with('project')
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();
    }

    /**
     * Obtener estadísticas rápidas para el widget del sidebar
     */
    private function getQuickStats($user, $productivityMetrics)
    {
        return [
            'today_productivity' => $productivityMetrics['today_productivity'] ?? 0,
            'pending_tasks' => $this->getPendingTasksCount($user),
            'current_session_minutes' => $productivityMetrics['current_session_minutes'] ?? 0,
            'active_projects' => $user->projects()->where('status', 'active')->count(),
        ];
    }
}