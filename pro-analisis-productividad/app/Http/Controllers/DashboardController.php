<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\TimeTracking;
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

        // --- 5. NUEVAS MÉTRICAS DE PRODUCTIVIDAD AVANZADAS ---
        $productivityMetrics = $this->calculateProductivityMetrics($user);
        $recommendations = $this->generateRecommendations($productivityMetrics);

        // --- 6. ESTADÍSTICAS DE ACTIVIDAD POR CATEGORÍA ---
        $activityStats = $this->getActivityStats($user);
        
        // --- 7. ENVIAR TODOS LOS DATOS A LA VISTA ---
        return view('dashboard', compact(
            'stats', 
            'recentActivities',
            'weeklyChartData',
            'activityTypeChartData',
            'productivityMetrics',
            'recommendations',
            'activityStats'
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
     * Calcular métricas de productividad avanzadas
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
            ];
        }

        $totalMinutes = $timeTrackings->sum('duration_minutes');
        $productiveMinutes = $timeTrackings->where('is_productive', true)->sum('duration_minutes');
        
        // Calcular consistencia (días con actividad)
        $daysWithActivity = $timeTrackings->groupBy(function($tracking) {
            return $tracking->start_time->format('Y-m-d');
        })->count();

        return [
            'efficiency_score' => $totalMinutes > 0 ? round(($productiveMinutes / $totalMinutes) * 100, 1) : 0,
            'focus_ratio' => round($timeTrackings->avg('focus_level'), 1),
            'consistency_score' => round(($daysWithActivity / 30) * 100, 1),
            'total_time_hours' => round($totalMinutes / 60, 1),
            'productive_time_hours' => round($productiveMinutes / 60, 1),
            'tasks_completed' => $timeTrackings->count(),
        ];
    }

    /**
     * Generar recomendaciones basadas en métricas
     */
    private function generateRecommendations($metrics)
    {
        $recommendations = [];

        if ($metrics['efficiency_score'] < 50) {
            $recommendations[] = "Tu eficiencia es baja. Considera eliminar distracciones durante el trabajo.";
        }

        if ($metrics['focus_ratio'] < 60) {
            $recommendations[] = "Intenta trabajar en sesiones más largas y enfocadas (mínimo 25 minutos).";
        }

        if ($metrics['consistency_score'] < 30) {
            $recommendations[] = "Trabaja en establecer una rutina más consistente.";
        }

        if ($metrics['total_time_hours'] < 10) {
            $recommendations[] = "Considera aumentar tu tiempo de trabajo semanal para mejores resultados.";
        }

        if (empty($recommendations)) {
            $recommendations[] = "¡Buen trabajo! Mantén tu consistencia y enfoque actual.";
        }

        return $recommendations;
    }
}