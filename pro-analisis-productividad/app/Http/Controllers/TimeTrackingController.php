<?php

namespace App\Http\Controllers;

use App\Models\TimeTracking;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeTrackingController extends Controller
{
    public function index()
    {
        $timeTrackings = Auth::user()->timeTrackings()->with('project')->latest()->get();
        return view('time-trackings.index', compact('timeTrackings'));
    }

    public function create()
    {
        $projects = Auth::user()->projects()->where('status', 'active')->get();
        return view('time-trackings.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string|max:500',
            'activity_type' => 'required|in:coding,meeting,research,debugging,documentation,other',
            'productivity_score' => 'required|integer|between:1,100'
        ]);

        // Calcular duración en minutos
        $duration = TimeTracking::calculateDuration(
            $validated['start_time'], 
            $validated['end_time']
        );

        TimeTracking::create([
            'user_id' => Auth::id(),
            'project_id' => $validated['project_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_minutes' => $duration,
            'description' => $validated['description'],
            'activity_type' => $validated['activity_type'],
            'productivity_score' => $validated['productivity_score'],
        ]);

        // Actualizar el total de horas del proyecto
        $project = Project::find($validated['project_id']);
        $project->total_hours += $duration;
        $project->save();

        return redirect()->route('time-trackings.index')
            ->with('success', 'Registro de tiempo guardado exitosamente.');
    }

    public function show(TimeTracking $timeTracking)
    {
        $this->authorize('view', $timeTracking);
        return view('time-trackings.show', compact('timeTracking'));
    }

    public function edit(TimeTracking $timeTracking)
    {
        $this->authorize('update', $timeTracking);
        $projects = Auth::user()->projects()->where('status', 'active')->get();
        return view('time-trackings.edit', compact('timeTracking', 'projects'));
    }

    public function update(Request $request, TimeTracking $timeTracking)
    {
        $this->authorize('update', $timeTracking);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string|max:500',
            'activity_type' => 'required|in:coding,meeting,research,debugging,documentation,other',
            'productivity_score' => 'required|integer|between:1,100'
        ]);

        // Calcular nueva duración
        $newDuration = TimeTracking::calculateDuration(
            $validated['start_time'], 
            $validated['end_time']
        );

        // Restar la duración anterior del proyecto
        $oldProject = Project::find($timeTracking->project_id);
        $oldProject->total_hours -= $timeTracking->duration_minutes;
        $oldProject->save();

        // Actualizar el registro
        $timeTracking->update([
            'project_id' => $validated['project_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_minutes' => $newDuration,
            'description' => $validated['description'],
            'activity_type' => $validated['activity_type'],
            'productivity_score' => $validated['productivity_score'],
        ]);

        // Sumar la nueva duración al proyecto
        $newProject = Project::find($validated['project_id']);
        $newProject->total_hours += $newDuration;
        $newProject->save();

        return redirect()->route('time-trackings.index')
            ->with('success', 'Registro de tiempo actualizado exitosamente.');
    }

    public function destroy(TimeTracking $timeTracking)
    {
        $this->authorize('delete', $timeTracking);

        // Restar las horas del proyecto
        $project = Project::find($timeTracking->project_id);
        $project->total_hours -= $timeTracking->duration_minutes;
        $project->save();

        $timeTracking->delete();

        return redirect()->route('time-trackings.index')
            ->with('success', 'Registro de tiempo eliminado exitosamente.');
    }
}