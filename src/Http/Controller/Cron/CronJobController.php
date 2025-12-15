<?php

namespace Goapptiv\Pulse\Http\Controller\Cron;

use Illuminate\Support\Facades\Artisan;
use Goapptiv\Pulse\Http\Controller\Controller;
use Goapptiv\Pulse\Http\Controller\RestResponse;

class CronJobController extends Controller
{

    /**
     * Fetch slow requests
     * 
     * @return JsonResponse
     */
    public function fetchSlowRequests()
    {
        Artisan::call('fetch:slow-requests');
        return RestResponse::done('message', 'success');
    }
}