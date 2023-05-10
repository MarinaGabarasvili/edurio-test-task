<?php

namespace App\Providers;

use App\Models\Survey;
use App\Services\SurveyService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SurveyService::class, function () {
            return new SurveyService($this->app->make(Survey::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
