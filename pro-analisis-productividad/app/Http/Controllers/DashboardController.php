<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\TimeTracking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // --- 1. DATOS PARA LAS TARJETAS DE ESTADÍSTICAS ---
        $stats = [
            'total_projects' => $user->projects()->count(),
            'active_projects' => $user->projects()->where('status', 'active')->count(),
            'weekly_hours' => $user->timeTrackings()
                ->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->sum('duration_minutes') / 60,
            'avg_productivity' => $user->timeTrackings()
                ->where('start_time', '>=', Carbon::now()->subDays(30)) // Productividad promedio de los últimos 30 días
                ->avg('productivity_score') ?: 0,
        ];

        // --- 2. DATOS PARA LA LISTA DE ACTIVIDAD RECIENTE ---
        $recentActivities = $user->timeTrackings()->with('project')->latest()->take(5)->get();

        // --- 3. DATOS PARA EL GRÁFICO DE ACTIVIDAD SEMANAL (BARRAS) ---
        $weeklyActivity = $user->timeTrackings()
            ->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->selectRaw('DAYOFWEEK(start_time) as day, SUM(duration_minutes) as total_minutes')
            ->groupBy('day')
            ->pluck('total_minutes', 'day')
            ->toArray();

        // Se prepara un array para los 7 días de la semana (1=Domingo, 2=Lunes, etc.)
        $weeklyChartDataRaw = array_fill(1, 7, 0);
        foreach ($weeklyActivity as $day => $minutes) {
            $weeklyChartDataRaw[$day] = round($minutes / 60, 2); // Convertir minutos a horas
        }
        // Se reordena el array para que la semana empiece en Lunes para el gráfico.
        $weeklyChartData = [
            $weeklyChartDataRaw[2], // Lunes
            $weeklyChartDataRaw[3], // Martes
            $weeklyChartDataRaw[4], // Miércoles
            $weeklyChartDataRaw[5], // Jueves
            $weeklyChartDataRaw[6], // Viernes
            $weeklyChartDataRaw[7], // Sábado
            $weeklyChartDataRaw[1]  // Domingo
        ];

        // --- 4. DATOS PARA EL GRÁFICO DE TIPO DE ACTIVIDAD (DONA) ---
        $activityTypes = $user->timeTrackings()
            ->where('start_time', '>=', Carbon::now()->subDays(30)) // Datos de los últimos 30 días
            ->selectRaw('activity_type, SUM(duration_minutes) as total_minutes')
            ->groupBy('activity_type')
            ->pluck('total_minutes', 'activity_type');

        $activityTypeChartData = [
            'labels' => $activityTypes->keys(),
            'data' => $activityTypes->values(),
        ];
        
        // --- 5. ENVIAR TODOS LOS DATOS A LA VISTA ---
        return view('dashboard', compact(
            'stats', 
            'recentActivities',
            'weeklyChartData',
            'activityTypeChartData'
        ));
    }
}