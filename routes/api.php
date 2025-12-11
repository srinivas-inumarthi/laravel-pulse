<?php

use Illuminate\Support\Facades\Route;
use Goapptiv\Pulse\Http\Controller\Cron\CronJobController;

$baseRoute = env(key: 'PULSE_BASE_ROUTE', default: 'pulse/api/');

Route::group(['prefix' => $baseRoute], function () {
    Route::get('slow-requests', [CronJobController::class, 'fetchSlowRequests']);
});