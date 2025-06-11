<?php

namespace App;

trait ApiResponseTrait
{
    /**
     * Send a success response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse(mixed $data = null, int $statusCode = 200)
    {
        return response()->json(['data' => $data, 'success' => true], $statusCode);
    }

    /**
     * Send an error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message, int $statusCode)
    {
        return response()->json(['error' => $message, 'success' => false], $statusCode);
    }
}
