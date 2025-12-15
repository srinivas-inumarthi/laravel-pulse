<?php

namespace Goapptiv\Pulse\Providers;

use Illuminate\Support\Facades\Event;
use Goapptiv\Pulse\Events\SlowRequest;
use Illuminate\Support\ServiceProvider;
use Goapptiv\Pulse\Console\SlowRequestsCommand;
use Goapptiv\Pulse\Listeners\SendSlowRequestNotification;
use Goapptiv\Pulse\Repositories\PulseEntryRepositoryInterface;
use Goapptiv\Pulse\Repositories\MySql\PulseEntryRepositoryImplementation;

class PulseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            SlowRequestsCommand::class
        ]);

        $this->app->bind(PulseEntryRepositoryInterface::class, PulseEntryRepositoryImplementation::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'laravel-pulse-migrations');


        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                SlowRequestsCommand::class
            ]);
        }

        // Events
        Event::listen(SlowRequest::class, SendSlowRequestNotification::class);
    }
}