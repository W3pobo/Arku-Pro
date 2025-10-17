<?php

namespace App\Http\Controllers;

use App\Models\TimeTracking;
use App\Models\Project;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeTrackingController extends Controller
{
    public function index()
    {
        $timeTrackings = Auth::user()->timeTrackings()
            ->with(['project', 'activityCategory'])
            ->latest()
            ->get();
            
        return view('time-trackings.index', compact('timeTrackings'));
    }

    public function create()
    {
        $projects = Auth::user()->projects()->where('status', 'active')->get();
        
        // Obtener categorías de actividad del usuario y del sistema
        $categories = ActivityCategory::where(function($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('is_system', true);
        })->get();

        return view('time-trackings.create', compact('projects', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'activity_category_id' => 'required|exists:activity_categories,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'required|string|max:500',
            'focus_level' => 'nullable|integer|min:0|max:100',
            'energy_level' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Calcular duración en minutos
            $start = new \DateTime($validated['start_time']);
            $end = new \DateTime($validated['end_time']);
            $duration = $start->diff($end)->h * 60 + $start->diff($end)->i;

            // Crear el registro de tiempo
            $timeTracking = TimeTracking::create([
                'user_id' => Auth::id(),
                'project_id' => $validated['project_id'],
                'activity_category_id' => $validated['activity_category_id'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'duration_minutes' => $duration,
                'description' => $validated['description'],
                'focus_level' => $validated['focus_level'] ?? 50,
                'energy_level' => $validated['energy_level'] ?? 50,
                'notes' => $validated['notes'],
            ]);

            // Actualizar el total de horas del proyecto si hay proyecto asociado
            if ($validated['project_id']) {
                $project = Project::find($validated['project_id']);
                $project->increment('total_hours', $duration);
            }

            return redirect()->route('time-trackings.index')
                ->with('success', 'Registro de tiempo guardado exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el registro: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function show(TimeTracking $timeTracking)
    {
        $this->authorize('view', $timeTracking);
        $timeTracking->load(['project', 'activityCategory']);
        return view('time-trackings.show', compact('timeTracking'));
    }

    public function edit(TimeTracking $timeTracking)
    {
        $this->authorize('update', $timeTracking);
        $projects = Auth::user()->projects()->where('status', 'active')->get();
        
        // Obtener categorías de actividad del usuario y del sistema
        $categories = ActivityCategory::where(function($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('is_system', true);
        })->get();

        return view('time-trackings.edit', compact('timeTracking', 'projects', 'categories'));
    }

    public function update(Request $request, TimeTracking $timeTracking)
    {
        $this->authorize('update', $timeTracking);

        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'activity_category_id' => 'required|exists:activity_categories,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'required|string|max:500',
            'focus_level' => 'nullable|integer|min:0|max:100',
            'energy_level' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calcular nueva duración
        $start = new \DateTime($validated['start_time']);
        $end = new \DateTime($validated['end_time']);
        $newDuration = $start->diff($end)->h * 60 + $start->diff($end)->i;

        // Manejar cambios de proyecto y horas
        $this->handleProjectHours($timeTracking, $validated['project_id'], $newDuration);

        // Actualizar el registro
        $timeTracking->update([
            'project_id' => $validated['project_id'],
            'activity_category_id' => $validated['activity_category_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_minutes' => $newDuration,
            'description' => $validated['description'],
            'focus_level' => $validated['focus_level'] ?? 50,
            'energy_level' => $validated['energy_level'] ?? 50,
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('time-trackings.index')
            ->with('success', 'Registro de tiempo actualizado exitosamente.');
    }

    public function destroy(TimeTracking $timeTracking)
    {
        $this->authorize('delete', $timeTracking);

        // Restar las horas del proyecto si existe
        if ($timeTracking->project_id) {
            $project = Project::find($timeTracking->project_id);
            $project->total_hours -= $timeTracking->duration_minutes;
            $project->save();
        }

        $timeTracking->delete();

        return redirect()->route('time-trackings.index')
            ->with('success', 'Registro de tiempo eliminado exitosamente.');
    }

    /**
     * Maneja la actualización de horas del proyecto cuando cambia el proyecto o la duración
     */
    private function handleProjectHours(TimeTracking $timeTracking, $newProjectId, $newDuration)
    {
        $oldProjectId = $timeTracking->project_id;
        $oldDuration = $timeTracking->duration_minutes;

        // Si cambió el proyecto, restar horas del proyecto anterior
        if ($oldProjectId && $oldProjectId != $newProjectId) {
            $oldProject = Project::find($oldProjectId);
            $oldProject->total_hours -= $oldDuration;
            $oldProject->save();
        }

        // Si hay nuevo proyecto, sumar las nuevas horas
        if ($newProjectId) {
            $newProject = Project::find($newProjectId);
            
            // Si es el mismo proyecto, ajustar la diferencia
            if ($oldProjectId == $newProjectId) {
                $difference = $newDuration - $oldDuration;
                $newProject->total_hours += $difference;
            } else {
                // Si es proyecto diferente, sumar la nueva duración
                $newProject->total_hours += $newDuration;
            }
            
            $newProject->save();
        }
    }
}