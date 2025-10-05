<?php
// app/Http/Controllers/ProductivityTagController.php

namespace App\Http\Controllers;

use App\Models\ProductivityTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductivityTagController extends Controller
{
    public function index()
    {
        $tags = ProductivityTag::where(function($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('is_system', true);
        })->orderBy('is_system', 'desc')
          ->orderBy('type')
          ->orderBy('name')
          ->get()
          ->groupBy('type');

        $tagTypes = [
            'focus_level' => 'Nivel de Concentración',
            'distraction' => 'Distracciones',
            'energy_level' => 'Nivel de Energía',
            'mood' => 'Estado de Ánimo',
            'environment' => 'Ambiente de Trabajo'
        ];

        return view('productivity-tags.index', compact('tags', 'tagTypes'));
    }

    public function create()
    {
        $tagTypes = [
            'focus_level' => 'Nivel de Concentración',
            'distraction' => 'Distracciones',
            'energy_level' => 'Nivel de Energía',
            'mood' => 'Estado de Ánimo',
            'environment' => 'Ambiente de Trabajo'
        ];

        return view('productivity-tags.create', compact('tagTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:productivity_tags,name,NULL,id,user_id,' . Auth::id(),
            'type' => 'required|string|max:50',
            'color' => 'required|string|max:7',
            'impact_score' => 'required|integer|min:-100|max:100',
            'description' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();

        ProductivityTag::create($validated);

        return redirect()->route('productivity-tags.index')
                         ->with('success', 'Etiqueta creada exitosamente.');
    }

    public function edit(ProductivityTag $productivityTag)
    {
        $this->authorize('update', $productivityTag);

        $tagTypes = [
            'focus_level' => 'Nivel de Concentración',
            'distraction' => 'Distracciones',
            'energy_level' => 'Nivel de Energía',
            'mood' => 'Estado de Ánimo',
            'environment' => 'Ambiente de Trabajo'
        ];

        return view('productivity-tags.edit', compact('productivityTag', 'tagTypes'));
    }

    public function update(Request $request, ProductivityTag $productivityTag)
    {
        $this->authorize('update', $productivityTag);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:productivity_tags,name,' . $productivityTag->id . ',id,user_id,' . Auth::id(),
            'type' => 'required|string|max:50',
            'color' => 'required|string|max:7',
            'impact_score' => 'required|integer|min:-100|max:100',
            'description' => 'nullable|string'
        ]);

        $productivityTag->update($validated);

        return redirect()->route('productivity-tags.index')
                         ->with('success', 'Etiqueta actualizada exitosamente.');
    }

    public function destroy(ProductivityTag $productivityTag)
    {
        $this->authorize('delete', $productivityTag);

        $productivityTag->delete();

        return redirect()->route('productivity-tags.index')
                         ->with('success', 'Etiqueta eliminada exitosamente.');
    }
}