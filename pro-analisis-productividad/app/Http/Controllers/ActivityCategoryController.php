<?php
// app/Http/Controllers/ActivityCategoryController.php

namespace App\Http\Controllers;

use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityCategoryController extends Controller
{
    public function index()
    {
        $categories = ActivityCategory::where(function($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('is_system', true);
        })->orderBy('is_system', 'desc')
          ->orderBy('name')
          ->get();

        return view('activity-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('activity-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:activity_categories,name,NULL,id,user_id,' . Auth::id(),
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'is_productive' => 'boolean',
            'productivity_weight' => 'required|integer|min:0|max:100'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_productive'] = $request->has('is_productive');

        ActivityCategory::create($validated);

        return redirect()->route('activity-categories.index')
                         ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(ActivityCategory $activityCategory)
    {
        $this->authorize('update', $activityCategory);
        
        return view('activity-categories.edit', compact('activityCategory'));
    }

    public function update(Request $request, ActivityCategory $activityCategory)
    {
        $this->authorize('update', $activityCategory);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:activity_categories,name,' . $activityCategory->id . ',id,user_id,' . Auth::id(),
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'is_productive' => 'boolean',
            'productivity_weight' => 'required|integer|min:0|max:100'
        ]);

        $validated['is_productive'] = $request->has('is_productive');

        $activityCategory->update($validated);

        return redirect()->route('activity-categories.index')
                         ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(ActivityCategory $activityCategory)
    {
        $this->authorize('delete', $activityCategory);

        if ($activityCategory->timeTrackings()->exists()) {
            return redirect()->back()
                             ->with('error', 'No se puede eliminar la categoría porque tiene registros de tiempo asociados.');
        }

        $activityCategory->delete();

        return redirect()->route('activity-categories.index')
                         ->with('success', 'Categoría eliminada exitosamente.');
    }
}