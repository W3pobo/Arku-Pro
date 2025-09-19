<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Auth::user()->projects()->latest()->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // MEJORA: Regla de validación más específica para el estado.
            'status' => 'required|in:active,paused,completed', 
        ]);

        // CORRECTO: Se usa la relación para crear el proyecto.
        Auth::user()->projects()->create($validatedData);
        
        // Se redirige con un mensaje de éxito.
        return redirect()->route('projects.index')
                       ->with('success', 'Proyecto creado exitosamente.');

        // -------------------------------------------------------------------
        // ELIMINADO: Este bloque de código era redundante y nunca se ejecutaba.
        // Project::create([ ... ]);
        // return redirect()->route('projects.index')->with('success', ...);
        // -------------------------------------------------------------------
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        // Agregamos el cálculo de horas para pasarlo a la vista 'show'
        $totalHours = $project->timeTrackings()->sum('duration_minutes') / 60;
        
        return view('projects.show', compact('project', 'totalHours'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        // Se guardan los datos validados en una variable.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,paused'
        ]);

        // MEJORA DE SEGURIDAD: Se usa $validatedData en lugar de $request->all().
        $project->update($validatedData);

        return redirect()->route('projects.index')->with('success', 'Proyecto actualizado exitosamente.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        
        // Lógica adicional: antes de borrar un proyecto, podrías querer borrar
        // o desasociar los registros de tiempo relacionados para mantener la BDD limpia.
        // $project->timeTrackings()->delete(); // Opcional

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado exitosamente.');
    }
}