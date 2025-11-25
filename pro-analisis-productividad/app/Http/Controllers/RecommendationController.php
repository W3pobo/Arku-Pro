<?php
// app/Http/Controllers/RecommendationController.php

namespace App\Http\Controllers;

use App\Services\TaskRecommenderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    private $recommenderService;

    public function __construct(TaskRecommenderService $recommenderService)
    {
        $this->recommenderService = $recommenderService;
    }

    public function getRecommendations()
    {
        $userId = Auth::id();
        $recommendations = $this->recommenderService->getRecommendations($userId, 5);

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations
        ]);
    }

    public function recordInteraction(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'interaction_type' => 'required|in:view,complete,create,edit,delete',
            'duration' => 'nullable|integer'
        ]);

        $this->recommenderService->recordInteraction(
            Auth::id(),
            $request->task_id,
            $request->interaction_type,
            $request->duration
        );

        return response()->json(['success' => true]);
    }

    public function showRecommendationsPage()
    {
        $userId = Auth::id();
        $recommendations = $this->recommenderService->getRecommendations($userId, 10);

        return view('recommendations.index', compact('recommendations'));
    }
}