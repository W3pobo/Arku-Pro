<?php

namespace App\Services;

use App\Models\User;
use App\Models\TimeTracking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IntegrationService
{
    public function syncWithGitHub(User $user, $accessToken)
    {
        try {
            $response = Http::withToken($accessToken)
                ->get('https://api.github.com/user/repos');
            
            $repos = $response->json();
            
            foreach ($repos as $repo) {
                // Sincronizar repositorios con proyectos
                $user->projects()->updateOrCreate(
                    ['github_id' => $repo['id']],
                    [
                        'name' => $repo['name'],
                        'description' => $repo['description'],
                        'url' => $repo['html_url'],
                        'is_public' => !$repo['private']
                    ]
                );
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('GitHub sync error: ' . $e->getMessage());
            return false;
        }
    }

    public function syncWithGitLab(User $user, $accessToken)
    {
        // Implementación similar para GitLab
    }

    public function syncWithJira(User $user, $domain, $email, $apiToken)
    {
        try {
            $response = Http::withBasicAuth($email, $apiToken)
                ->get("https://{$domain}.atlassian.net/rest/api/3/project");
            
            $projects = $response->json()['values'];
            
            foreach ($projects as $project) {
                $user->projects()->updateOrCreate(
                    ['jira_id' => $project['id']],
                    [
                        'name' => $project['name'],
                        'key' => $project['key'],
                        'url' => "https://{$domain}.atlassian.net/browse/{$project['key']}"
                    ]
                );
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Jira sync error: ' . $e->getMessage());
            return false;
        }
    }

    public function importCommitsAsTimeEntries(User $user, $commits)
    {
        foreach ($commits as $commit) {
            TimeTracking::create([
                'user_id' => $user->id,
                'project_id' => $this->findProjectByRepo($user, $commit['repo']),
                'start_time' => $commit['date'],
                'end_time' => $commit['date'],
                'duration_minutes' => 30, // Estimación por defecto
                'activity_type' => 'coding',
                'productivity_score' => 85,
                'description' => "Commit: {$commit['message']}",
                'external_id' => $commit['id'],
                'external_type' => 'github_commit'
            ]);
        }
    }
}