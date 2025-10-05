<?php

namespace App\Http\Controllers;

use App\Models\TimeTracking;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\ProductivityCalculator;

class ReportController extends Controller
{
    protected $productivityCalculator;

    public function __construct(ProductivityCalculator $productivityCalculator)
    {
        $this->productivityCalculator = $productivityCalculator;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Obtener métricas completas de productividad
        $productivityMetrics = $this->productivityCalculator->calculateUserProductivity($user->id);
        $recommendations = $this->productivityCalculator->generateRecommendations($productivityMetrics);

        return view('reports.index', [
            'user' => $user,
            'weeklyStats' => $this->getWeeklyStats($user),
            'monthlyStats' => $this->getMonthlyStats($user),
            'projectStats' => $this->getProjectStats($user),
            'activityStats' => $this->getActivityStats($user),
            'productivityMetrics' => $productivityMetrics,
            'recommendations' => $recommendations
        ]);
    }

    public function productivity(Request $request)
    {
        $user = Auth::user();
        
        // Manejar filtros de fecha
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        // Obtener métricas completas con filtros
        $productivityMetrics = $this->productivityCalculator->calculateUserProductivity(
            $user->id, 
            $startDate, 
            $endDate
        );
        
        $recommendations = $this->productivityCalculator->generateRecommendations($productivityMetrics);
        $productivityData = $this->getProductivityTrends($user);
        
        return view('reports.productivity', [
            'user' => $user,
            'dailyTrends' => $productivityData['daily'],
            'weeklyTrends' => $productivityData['weekly'],
            'activityEfficiency' => $productivityData['activity_efficiency'],
            'metrics' => $productivityMetrics,
            'recommendations' => $recommendations,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function projectReport(Project $project, Request $request)
    {
        $this->authorize('view', $project);
        
        // Manejar filtros de fecha para el proyecto
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        // Obtener métricas específicas del proyecto
        $projectMetrics = $this->productivityCalculator->calculateProjectProductivity(
            $project->id, 
            $startDate, 
            $endDate
        );

        $timeTrackings = $project->timeTrackings()
            ->when($startDate, function($query) use ($startDate) {
                return $query->where('start_time', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->where('start_time', '<=', $endDate);
            })
            ->orderBy('start_time', 'desc')
            ->get();

        $stats = [
            'total_hours' => $timeTrackings->sum('duration_minutes') / 60,
            'avg_productivity' => $timeTrackings->avg('productivity_score'),
            'activity_breakdown' => $timeTrackings->groupBy('activity_type')->map->count(),
            'weekly_trend' => $this->getProjectWeeklyTrend($project),
            'metrics' => $projectMetrics
        ];

        return view('reports.project', compact('project', 'timeTrackings', 'stats'));
    }

    private function getWeeklyStats(User $user)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return TimeTracking::where('user_id', $user->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->selectRaw('
                SUM(duration_minutes) as total_minutes,
                AVG(productivity_score) as avg_productivity,
                COUNT(*) as total_sessions,
                DAYNAME(start_time) as day_name
            ')
            ->groupBy('day_name')
            ->orderByRaw('FIELD(day_name, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
            ->get();
    }

    private function getProductivityTrends(User $user)
    {
        // Datos para los últimos 30 días
        $startDate = Carbon::now()->subDays(30);
        
        $dailyTrends = TimeTracking::where('user_id', $user->id)
            ->where('start_time', '>=', $startDate)
            ->selectRaw('
                DATE(start_time) as date,
                AVG(productivity_score) as avg_productivity,
                SUM(duration_minutes) as total_minutes
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $activityEfficiency = TimeTracking::where('user_id', $user->id)
            ->where('start_time', '>=', $startDate)
            ->selectRaw('
                activity_type,
                AVG(productivity_score) as avg_efficiency,
                SUM(duration_minutes) as total_time,
                COUNT(*) as sessions
            ')
            ->groupBy('activity_type')
            ->get();

        return [
            'daily' => $dailyTrends,
            'weekly' => $dailyTrends->groupBy(function($item) {
                return Carbon::parse($item->date)->weekOfYear;
            }),
            'activity_efficiency' => $activityEfficiency
        ];
    }

    private function getMonthlyStats(User $user)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return TimeTracking::where('user_id', $user->id)
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->selectRaw('
                SUM(duration_minutes) as total_minutes,
                AVG(productivity_score) as avg_productivity,
                COUNT(*) as total_sessions,
                DATE(start_time) as date
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getProjectStats(User $user)
{
    return TimeTracking::where('time_trackings.user_id', $user->id) // Especifica la tabla
        ->join('projects', 'time_trackings.project_id', '=', 'projects.id')
        ->selectRaw('
            projects.name as project_name,
            projects.id as project_id,
            SUM(time_trackings.duration_minutes) as total_minutes,
            AVG(time_trackings.focus_level) as avg_focus,
            AVG(time_trackings.energy_level) as avg_energy,
            COUNT(*) as session_count
        ')
        ->groupBy('projects.name', 'projects.id')
        ->orderBy('total_minutes', 'desc')
        ->get();
}

    private function getActivityStats(User $user)
{
    $startDate = Carbon::now()->subDays(30);

    return TimeTracking::where('time_trackings.user_id', $user->id) // Especifica la tabla
        ->where('start_time', '>=', $startDate)
        ->join('activity_categories', 'time_trackings.activity_category_id', '=', 'activity_categories.id')
        ->selectRaw('
            activity_categories.name as activity_type,
            activity_categories.icon as activity_icon,
            activity_categories.color as activity_color,
            SUM(time_trackings.duration_minutes) as total_minutes,
            AVG(time_trackings.focus_level) as avg_focus,
            AVG(time_trackings.energy_level) as avg_energy,
            COUNT(*) as total_sessions
        ')
        ->groupBy('activity_categories.name', 'activity_categories.id', 'activity_categories.icon', 'activity_categories.color')
        ->orderBy('total_minutes', 'desc')
        ->get();
}

    /**
     * Obtiene tendencia semanal para un proyecto específico
     */
    private function getProjectWeeklyTrend(Project $project)
    {
        $startDate = Carbon::now()->subWeeks(4);
        
        return TimeTracking::where('project_id', $project->id)
            ->where('start_time', '>=', $startDate)
            ->selectRaw('
                YEARWEEK(start_time) as week,
                SUM(duration_minutes) as total_minutes,
                AVG(productivity_score) as avg_productivity
            ')
            ->groupBy('week')
            ->orderBy('week')
            ->get();
    }

    /**
     * Nuevo método para obtener reporte comparativo
     */
    public function comparativeReport(Request $request)
    {
        $user = Auth::user();
        $period1Start = $request->get('period1_start') ? Carbon::parse($request->get('period1_start')) : Carbon::now()->subDays(30);
        $period1End = $request->get('period1_end') ? Carbon::parse($request->get('period1_end')) : Carbon::now()->subDays(15);
        $period2Start = $request->get('period2_start') ? Carbon::parse($request->get('period2_start')) : Carbon::now()->subDays(14);
        $period2End = $request->get('period2_end') ? Carbon::parse($request->get('period2_end')) : Carbon::now();

        $period1Metrics = $this->productivityCalculator->calculateUserProductivity(
            $user->id, 
            $period1Start, 
            $period1End
        );

        $period2Metrics = $this->productivityCalculator->calculateUserProductivity(
            $user->id, 
            $period2Start, 
            $period2End
        );

        return view('reports.comparative', [
            'user' => $user,
            'period1' => [
                'metrics' => $period1Metrics,
                'start' => $period1Start,
                'end' => $period1End
            ],
            'period2' => [
                'metrics' => $period2Metrics,
                'start' => $period2Start,
                'end' => $period2End
            ]
        ]);
    }
}