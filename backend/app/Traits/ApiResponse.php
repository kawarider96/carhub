<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * @mixin \Illuminate\Routing\MiddlewareNameResolver
 */
trait ApiResponse
{
    /**
     * Sikeres JSON válasz
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function success($data = null, string $message = 'Sikeres művelet', int $status = 200): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Hibás JSON válasz
     *
     * @param string $message
     * @param int $status
     * @param array<string, mixed>|string|null $errors
     * @return JsonResponse
     */
    protected function error(string $message = 'Hiba történt', int $status = 400, array|string|null $errors = null): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}
