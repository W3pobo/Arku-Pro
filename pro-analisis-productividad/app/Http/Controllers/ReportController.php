<?php

namespace App\Http\Controllers;

use App\Models\TimeTracking;
use App\Models\Project;
use App\Models\User;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Obtener métricas básicas
        $weeklyStats = $this->getWeeklyStats($user);
        $monthlyStats = $this->getMonthlyStats($user);
        $projectStats = $this->getProjectStats($user);
        $activityStats = $this->getActivityStats($user);

        return view('reports.index', [
            'user' => $user,
            'weeklyStats' => $weeklyStats,
            'monthlyStats' => $monthlyStats,
            'projectStats' => $projectStats,
            'activityStats' => $activityStats,
        ]);
    }

    public function productivity(Request $request)
{
    $user = Auth::user();
    
    // Manejar filtros de fecha
    $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays(30);
    $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

    // Obtener métricas CORREGIDAS
    $hoursThisWeek = $this->getHoursThisWeek($user);
    $averageProductivity = $this->getAverageProductivity($user);
    $workSessions = $this->getWorkSessionsCount($user);
    $activeProjects = $this->getActiveProjectsCount($user);
    $weeklyProductivity = $this->getWeeklyProductivity($user);
    $activityDistribution = $this->getActivityDistribution($user);
    $projectPerformance = $this->getProjectPerformance($user);

    return view('reports.productivity', compact(
        'hoursThisWeek',
        'averageProductivity',
        'workSessions',
        'activeProjects',
        'weeklyProductivity',
        'activityDistribution',
        'projectPerformance',
        'startDate',
        'endDate'
    ));
}

    public function projectReport(Project $project, Request $request)
    {
        $this->authorize('view', $project);
        
        // Manejar filtros de fecha para el proyecto
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

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
            'avg_focus' => $timeTrackings->avg('focus_level') ?? 0,
            'avg_energy' => $timeTrackings->avg('energy_level') ?? 0,
            'activity_breakdown' => $timeTrackings->groupBy('activity_category_id')->map->count(),
        ];

        return view('reports.project', compact('project', 'timeTrackings', 'stats'));
    }

    /**
     * Métodos para el reporte de productividad
     */
    private function getHoursThisWeek($user)
{
    return TimeTracking::where('time_trackings.user_id', $user->id) // Especificar tabla
        ->whereBetween('time_trackings.start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->sum('time_trackings.duration_minutes') / 60;
}

    private function getAverageProductivity($user)
{
    $avgFocus = TimeTracking::where('time_trackings.user_id', $user->id) // Especificar tabla
        ->where('time_trackings.start_time', '>=', Carbon::now()->subDays(30))
        ->avg('time_trackings.focus_level') ?? 0;
        
    $avgEnergy = TimeTracking::where('time_trackings.user_id', $user->id) // Especificar tabla
        ->where('time_trackings.start_time', '>=', Carbon::now()->subDays(30))
        ->avg('time_trackings.energy_level') ?? 0;
        
    return round(($avgFocus + $avgEnergy) / 2, 1);
}

    private function getWorkSessionsCount($user)
{
    return TimeTracking::where('time_trackings.user_id', $user->id) // Especificar tabla
        ->whereBetween('time_trackings.start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->count();
}

    private function getActiveProjectsCount($user)
    {
        return Project::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();
    }

    private function getWeeklyProductivity($user)
{
    return TimeTracking::where('time_trackings.user_id', $user->id) // Especificar tabla
        ->whereBetween('time_trackings.start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->select(
            DB::raw('DAYNAME(time_trackings.start_time) as day_name'),
            DB::raw('DAYOFWEEK(time_trackings.start_time) as day_number'),
            DB::raw('AVG((time_trackings.focus_level + time_trackings.energy_level) / 2) as productivity'),
            DB::raw('SUM(time_trackings.duration_minutes) as total_minutes')
        )
        ->groupBy('day_name', 'day_number')
        ->orderBy('day_number')
        ->get();
}

    /**
     * DISTRIBUCIÓN POR TIPO DE ACTIVIDAD - NUEVO MÉTODO
     */
    private function getActivityDistribution($user)
{
    return TimeTracking::where('time_trackings.user_id', $user->id) // Especificar la tabla
        ->where('time_trackings.start_time', '>=', Carbon::now()->subDays(30))
        ->join('activity_categories', 'time_trackings.activity_category_id', '=', 'activity_categories.id')
        ->select(
            'activity_categories.name as category_name',
            DB::raw('SUM(time_trackings.duration_minutes) as total_minutes'),
            DB::raw('COUNT(time_trackings.id) as sessions_count'),
            DB::raw('AVG((time_trackings.focus_level + time_trackings.energy_level) / 2) as avg_productivity')
        )
        ->groupBy('activity_categories.id', 'activity_categories.name')
        ->orderByDesc('total_minutes')
        ->get()
        ->map(function ($item) {
            return [
                'category' => $item->category_name,
                'hours' => round($item->total_minutes / 60, 1),
                'sessions' => $item->sessions_count,
                'productivity' => round($item->avg_productivity, 1)
            ];
        });
}

    private function getProjectPerformance($user)
    {
        return Project::where('user_id', $user->id)
            ->withCount(['timeTrackings as sessions_count' => function ($query) {
                $query->where('start_time', '>=', Carbon::now()->subDays(30));
            }])
            ->withSum(['timeTrackings as total_minutes' => function ($query) {
                $query->where('start_time', '>=', Carbon::now()->subDays(30));
            }], 'duration_minutes')
            ->withAvg(['timeTrackings as avg_focus' => function ($query) {
                $query->where('start_time', '>=', Carbon::now()->subDays(30));
            }], 'focus_level')
            ->withAvg(['timeTrackings as avg_energy' => function ($query) {
                $query->where('start_time', '>=', Carbon::now()->subDays(30));
            }], 'energy_level')
            ->get()
            ->map(function ($project) {
                $avgProductivity = (($project->avg_focus ?? 0) + ($project->avg_energy ?? 0)) / 2;
                
                return [
                    'name' => $project->name,
                    'total_hours' => round(($project->total_minutes ?? 0) / 60, 1),
                    'productivity' => round($avgProductivity, 1),
                    'sessions' => $project->sessions_count ?? 0,
                    'status' => $project->status
                ];
            });
    }

    /**
     * Métodos existentes para otros reportes
     */
    private function getWeeklyStats(User $user)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return TimeTracking::where('user_id', $user->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->selectRaw('
                SUM(duration_minutes) as total_minutes,
                AVG((focus_level + energy_level) / 2) as avg_productivity,
                COUNT(*) as total_sessions,
                DAYNAME(start_time) as day_name
            ')
            ->groupBy('day_name')
            ->orderByRaw('FIELD(day_name, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
            ->get();
    }

    private function getMonthlyStats(User $user)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return TimeTracking::where('user_id', $user->id)
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->selectRaw('
                SUM(duration_minutes) as total_minutes,
                AVG((focus_level + energy_level) / 2) as avg_productivity,
                COUNT(*) as total_sessions,
                DATE(start_time) as date
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getProjectStats(User $user)
    {
        return TimeTracking::where('time_trackings.user_id', $user->id)
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

        return TimeTracking::where('time_trackings.user_id', $user->id)
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
}