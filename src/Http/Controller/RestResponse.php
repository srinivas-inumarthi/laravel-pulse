<?php

namespace Goapptiv\Pulse\Http\Controller;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as ResponseConstants;

class RestResponse
{
    /**
     * Handle errors and return Bad Request
     *
     * @param $errors
     * @return JsonResponse
     */
    public static function badRequest($errors)
    {
        return Response::json(
            ['success' => false, 'errors' => $errors],
            ResponseConstants::HTTP_BAD_REQUEST
        );
    }

    /**
     * Success response
     * Also prepare total details for pagination
     *
     * @param $root
     * @param $data
     * @return mixed
     */
    public static function done($root, $data)
    {
        return self::successResponse([$root => $data]);
    }

    /**
     * Success response
     *
     * @param $response
     * @return mixed
     */
    public static function successResponse($response)
    {
        $response['success'] = true;
        return Response::json($response);
    }
}