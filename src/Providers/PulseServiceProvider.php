<?php

namespace Goapptiv\Pulse\Providers;

use Illuminate\Support\Facades\Event;
use Goapptiv\Pulse\Events\SlowRequest;
use Goapptiv\Pulse\Events\FailedRequest;
use Illuminate\Support\ServiceProvider;
use Goapptiv\Pulse\Console\SlowRequestsCommand;
use Goapptiv\Pulse\Console\FailedRequestsCommand;
use Goapptiv\Pulse\Listeners\SendSlowRequestNotification;
use Goapptiv\Pulse\Listeners\SendFailedRequestNotification;
use Goapptiv\Pulse\Repositories\PulseEntryRepositoryInterface;
use Goapptiv\Pulse\Repositories\MySql\PulseEntryRepositoryImplementation;
use Goapptiv\Pulse\Repositories\PulseEventCommunicationRepositoryInterface;
use Goapptiv\Pulse\Repositories\MySql\PulseEventCommunicationRepositoryImplementation;

class PulseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                SlowRequestsCommand::class,
                FailedRequestsCommand::class
            ]);
        }
        
        // Repositories
        $this->app->bind(PulseEntryRepositoryInterface::class, PulseEntryRepositoryImplementation::class);
        $this->app->bind(PulseEventCommunicationRepositoryInterface::class,PulseEventCommunicationRepositoryImplementation::class);
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

        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                SlowRequestsCommand::class,
                FailedRequestsCommand::class
            ]);
        }

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        // Events
        Event::listen(SlowRequest::class, SendSlowRequestNotification::class);
        Event::listen(FailedRequest::class, SendFailedRequestNotification::class);
    }
}