<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TimeTracking;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductivityDataController extends Controller
{
    public function getUserProductivityData(User $user, Request $request)
    {
        // Verificar autenticación y permisos
        if (!$request->user()->tokenCan('productivity:read') || $request->user()->id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $dateRange = $request->validate([
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date'
        ]);

        $data = [
            'user' => $user->only(['id', 'name', 'email']),
            'time_trackings' => $this->getTimeTrackingsData($user, $dateRange),
            'projects' => $this->getProjectsData($user),
            'productivity_metrics' => $this->calculateProductivityMetrics($user, $dateRange),
            'activity_analysis' => $this->analyzeActivities($user, $dateRange)
        ];

        return response()->json($data);
    }

    public function getTimeTrackings(User $user, Request $request)
    {
        if (!$request->user()->tokenCan('productivity:read') || $request->user()->id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = TimeTracking::where('user_id', $user->id)
            ->with('project')
            ->orderBy('start_time', 'desc');

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('start_date')) {
            $query->where('start_time', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('end_time', '<=', $request->end_date);
        }

        return response()->json([
            'data' => $query->paginate($request->per_page ?? 50)
        ]);
    }

    public function storeRecommendations(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recommendations' => 'required|array',
            'recommendations.*.type' => 'required|string',
            'recommendations.*.message' => 'required|string',
            'recommendations.*.priority' => 'required|in:low,medium,high',
            'analysis_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Almacenar recomendaciones del análisis de Python
        $user->recommendations()->createMany($request->recommendations);

        return response()->json(['message' => 'Recommendations stored successfully']);
    }

    // Métodos para integraciones externas
    public function handleGitHubWebhook(Request $request)
    {
        $payload = $request->all();
        $event = $request->header('X-GitHub-Event');

        // Procesar eventos de GitHub (commits, PRs, etc.)
        // Esto se integraría con el sistema de time tracking

        return response()->json(['status' => 'processed']);
    }

    private function getTimeTrackingsData(User $user, array $dateRange = [])
    {
        $query = TimeTracking::where('user_id', $user->id);

        if (!empty($dateRange)) {
            $query->whereBetween('start_time', [$dateRange['start_date'], $dateRange['end_date']]);
        }

        return $query->get()->map(function($tracking) {
            return [
                'id' => $tracking->id,
                'project_id' => $tracking->project_id,
                'start_time' => $tracking->start_time,
                'end_time' => $tracking->end_time,
                'duration_minutes' => $tracking->duration_minutes,
                'activity_type' => $tracking->activity_type,
                'productivity_score' => $tracking->productivity_score,
                'description' => $tracking->description
            ];
        });
    }

    // ... otros métodos auxiliares
}