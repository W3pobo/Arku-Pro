<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TaskRecommenderService;
use Illuminate\Support\Facades\Auth;

class TrackUserInteractions
{
    private $recommenderService;

    public function __construct(TaskRecommenderService $recommenderService)
    {
        $this->recommenderService = $recommenderService;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Capturar interacciones con tareas
        if (Auth::check() && $this->shouldTrack($request)) {
            $this->trackInteraction($request);
        }

        return $response;
    }

    private function shouldTrack(Request $request)
    {
        return $request->routeIs('tasks.show') || 
               $request->routeIs('tasks.complete') ||
               $request->routeIs('tasks.edit');
    }

    private function trackInteraction(Request $request)
    {
        $taskId = $request->route('task') ?? $request->route('id');
        
        if ($taskId) {
            $interactionType = $this->getInteractionType($request);
            $this->recommenderService->recordInteraction(
                Auth::id(),
                $taskId,
                $interactionType
            );
        }
    }

    private function getInteractionType(Request $request)
    {
        if ($request->routeIs('tasks.show')) return 'view';
        if ($request->routeIs('tasks.complete')) return 'complete';
        if ($request->routeIs('tasks.edit')) return 'edit';
        
        return 'view';
    }
}