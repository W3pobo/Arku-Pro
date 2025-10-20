<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Project;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'all');
        $projectId = $request->get('project_id');
        
        $tasks = $user->tasks()
            ->with('project')
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($projectId, function($query) use ($projectId) {
                return $query->where('project_id', $projectId);
            })
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $projects = $user->projects()->where('status', 'active')->get();
        $stats = [
            'total' => $user->tasks()->count(),
            'pending' => $user->tasks()->pending()->count(),
            'in_progress' => $user->tasks()->inProgress()->count(),
            'completed' => $user->tasks()->completed()->count(),
            'overdue' => $user->tasks()->overdue()->count(),
        ];

        return view('tasks.index', compact('tasks', 'projects', 'stats', 'status', 'projectId'));
    }

    public function create()
    {
        $user = Auth::user();
        $projects = $user->projects()->where('status', 'active')->get();
        return view('tasks.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:1,2,3',
            'due_date' => 'nullable|date',
            'estimated_minutes' => 'nullable|integer|min:0',
        ]);

        $task = Task::create([
            'project_id' => $request->project_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'estimated_minutes' => $request->estimated_minutes,
            'status' => 'pending',
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea creada exitosamente.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        $task->load('project', 'timeTrackings');
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        
        $user = Auth::user();
        $projects = $user->projects()->where('status', 'active')->get();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:1,2,3',
            'due_date' => 'nullable|date',
            'estimated_minutes' => 'nullable|integer|min:0',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea actualizada exitosamente.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        $task->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea eliminada exitosamente.');
    }
}