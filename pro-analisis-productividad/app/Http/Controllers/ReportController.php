<?php

namespace App\Http\Controllers;

use App\Models\TimeTracking;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('reports.index', [
            'user' => $user,
            'weeklyStats' => $this->getWeeklyStats($user),
            'monthlyStats' => $this->getMonthlyStats($user),
            'projectStats' => $this->getProjectStats($user),
            'activityStats' => $this->getActivityStats($user)
        ]);
    }

    public function productivity()
    {
        $user = Auth::user();
        $productivityData = $this->getProductivityTrends($user);
        
        return view('reports.productivity', [
            'user' => $user,
            'dailyTrends' => $productivityData['daily'],
            'weeklyTrends' => $productivityData['weekly'],
            'activityEfficiency' => $productivityData['activity_efficiency']
        ]);
    }

    public function projectReport(Project $project)
    {
        $this->authorize('view', $project);
        
        $timeTrackings = $project->timeTrackings()
            ->orderBy('start_time', 'desc')
            ->get();

        $stats = [
            'total_hours' => $timeTrackings->sum('duration_minutes') / 60,
            'avg_productivity' => $timeTrackings->avg('productivity_score'),
            'activity_breakdown' => $timeTrackings->groupBy('activity_type')->map->count(),
            'weekly_trend' => $this->getProjectWeeklyTrend($project)
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
    // ... otros métodos de análisis

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


// ... otros métodos de análisis

    private function getProjectStats(User $user)
    {
        return TimeTracking::where('time_trackings.user_id', $user->id)
            ->join('projects', 'time_trackings.project_id', '=', 'projects.id')
            ->selectRaw('
                projects.name as project_name,
                SUM(time_trackings.duration_minutes) as total_minutes
            ')
            ->groupBy('projects.name')
            ->orderBy('total_minutes', 'desc')
            ->get();
    }

    private function getActivityStats(User $user)
    {
        // Puedes ajustar el rango de fechas si lo necesitas, por ejemplo, para el último mes.
        $startDate = Carbon::now()->subDays(30);

        return TimeTracking::where('user_id', $user->id)
            ->where('start_time', '>=', $startDate)
            ->selectRaw('
                activity_type,
                SUM(duration_minutes) as total_minutes,
                AVG(productivity_score) as avg_productivity,
                COUNT(*) as total_sessions
            ')
            ->groupBy('activity_type')
            ->orderBy('total_minutes', 'desc')
            ->get();
    }
}