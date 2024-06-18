<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResponseHelper
{
    /**
     * @param bool $status
     * @param string|null $message
     * @param mixed $data
     * @param int $responseCode
     * @param mixed $errorCode
     * @return JsonResponse
     */
    public static function json(bool $status = true, string $message = null, mixed $data = [], int $responseCode = Response::HTTP_OK, mixed $errorCode = null): JsonResponse
    {
        $response = [];
        if ($status) {
            if ($message) {
                $response['message'] = $message;
            }
        } else {
            $response['error']['code'] = $errorCode ?? $responseCode;
            $response['error']['message'] = $message;
        }

        if ($data) {
            $response['data'] = $data;
        }

        $responseCode = $responseCode < 600 ? $responseCode : Response::HTTP_BAD_REQUEST;

        $response['status'] = $status;

        return response()->json($response, $responseCode);
    }

    /**
     * @param mixed $data
     * @param string|null $message
     * @param int $responseCode
     * @return JsonResponse
     */
    public static function success(mixed $data, string $message = null, int $responseCode = Response::HTTP_OK): JsonResponse
    {
        return self::json(true, $message, $data, $responseCode);
    }

    /**
     * @param string|null $message
     * @param int|null $responseCode
     * @param mixed $errorCode
     * @return JsonResponse
     */
    public static function error(string $message = null, ?int $responseCode, mixed $data = [], mixed $errorCode = null)
    {
        return self::json(false, $message, $data, $responseCode, $errorCode);
    }

    /**
     * @param string|null $message
     * @param mixed $data
     * @param mixed $errorCode
     * @return JsonResponse
     */
    public static function error404(string $message = null, mixed $data = [], mixed $errorCode = null)
    {
        return self::error($message, Response::HTTP_NOT_FOUND, $data, $errorCode);
    }

    /**
     * @param string|null $message
     * @param mixed $data
     * @param mixed $errorCode
     * @return JsonResponse
     */
    public static function error401(string $message = null, mixed $data = [], mixed $errorCode = null)
    {
        return self::error($message, Response::HTTP_UNAUTHORIZED, $data, $errorCode);
    }

    /**
     * @param string|null $message
     * @param mixed $data
     * @param mixed $errorCode
     * @return JsonResponse
     */
    public static function error403(string $message = null, mixed $data = [], mixed $errorCode = null)
    {
        return self::error($message, Response::HTTP_FORBIDDEN, $data, $errorCode);
    }

    /**
     * @param string|null $message
     * @param mixed $data
     * @param mixed $errorCode
     * @return JsonResponse
     */
    public static function error422(string $message = null, mixed $data = [], mixed $errorCode = null)
    {
        return self::error($message, Response::HTTP_UNPROCESSABLE_ENTITY, $data, $errorCode);
    }

    /**
     * @param string|null $message
     * @param mixed $data
     * @param mixed $errorCode
     * @return JsonResponse
     */
    public static function error500(string $message = null, mixed $data = [], mixed $errorCode = null)
    {
        return self::error($message, Response::HTTP_INTERNAL_SERVER_ERROR, $data, $errorCode);
    }

    /**
     * @param string|null $message
     * @param mixed $data
     * @param mixed $errorCode
     * @return JsonResponse
     */
    public static function error400(string $message = null, mixed $data = [], mixed $errorCode = null)
    {
        return self::error($message, Response::HTTP_BAD_REQUEST, $data, $errorCode);
    }
}
