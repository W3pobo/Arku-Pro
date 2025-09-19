<?php

namespace App\Http\Controllers;

use App\Services\IntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegrationController extends Controller
{
    protected $integrationService;

    public function __construct(IntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    public function connectGitHub(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string'
        ]);

        $success = $this->integrationService->syncWithGitHub(
            Auth::user(),
            $request->access_token
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'GitHub connected successfully' : 'Failed to connect GitHub'
        ]);
    }

    public function connectJira(Request $request)
    {
        $request->validate([
            'domain' => 'required|string',
            'email' => 'required|email',
            'api_token' => 'required|string'
        ]);

        $success = $this->integrationService->syncWithJira(
            Auth::user(),
            $request->domain,
            $request->email,
            $request->api_token
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Jira connected successfully' : 'Failed to connect Jira'
        ]);
    }

    public function getIntegrations()
    {
        $user = Auth::user();
        
        return response()->json([
            'github_connected' => $user->github_token !== null,
            'gitlab_connected' => $user->gitlab_token !== null,
            'jira_connected' => $user->jira_token !== null
        ]);
    }
}