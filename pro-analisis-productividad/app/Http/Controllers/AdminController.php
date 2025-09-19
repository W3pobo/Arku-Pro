<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\TimeTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'total_hours' => TimeTracking::sum('duration_minutes') / 60,
            'avg_productivity' => TimeTracking::avg('productivity_score')
        ];

        $recentActivity = TimeTracking::with(['user', 'project'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $userProductivity = User::withCount(['timeTrackings as total_hours' => function($query) {
                $query->select(DB::raw('SUM(duration_minutes) / 60'));
            }])
            ->withAvg('timeTrackings as avg_productivity', 'productivity_score')
            ->orderBy('total_hours', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentActivity', 'userProductivity'));
    }

    public function users()
    {
        $users = User::withCount(['projects', 'timeTrackings'])
            ->withSum('timeTrackings as total_minutes', 'duration_minutes')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function projects()
    {
        $projects = Project::with(['user', 'timeTrackings'])
            ->withCount('timeTrackings')
            ->withSum('timeTrackings as total_minutes', 'duration_minutes')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.projects', compact('projects'));
    }

    public function systemReport()
    {
        // Reporte detallado del sistema
        $report = [
            'users_by_month' => User::selectRaw('YEAR(created_at) year, MONTH(created_at) month, COUNT(*) count')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get(),
            'productivity_trends' => TimeTracking::selectRaw('
                    DATE_FORMAT(start_time, "%Y-%m") as month,
                    AVG(productivity_score) as avg_productivity,
                    SUM(duration_minutes) / 60 as total_hours
                ')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get(),
            'activity_distribution' => TimeTracking::selectRaw('
                    activity_type,
                    COUNT(*) as count,
                    SUM(duration_minutes) / 60 as total_hours
                ')
                ->groupBy('activity_type')
                ->get()
        ];

        return view('admin.system-report', compact('report'));
    }
}