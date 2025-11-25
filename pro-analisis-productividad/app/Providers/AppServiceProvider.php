<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TaskRecommenderService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TaskRecommenderService::class, function ($app) {
            return new TaskRecommenderService();
        });
    }

    public function boot()
    {
        //
    }
}