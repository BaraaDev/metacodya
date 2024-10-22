<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * General API response
     *
     * @param mixed|null $data
     * @param string     $message
     * @param int        $code
     * @param array      $errors
     * @return JsonResponse
     */
    public function apiResponse(mixed $data = null, string $message = "", int $code = 200, array $errors = []) : JsonResponse
    {
        $response = [
            'status'  => $this->isSuccessStatusCode($code),
            'message' => $message,
            'data'    => $data,
            'errors'  => $errors,
        ];

        return response()->json($response, $code);
    }

    /**
     * Determine if the status code is a success code
     *
     * @param int $code
     * @return bool
     */
    protected function isSuccessStatusCode(int $code): bool
    {
        return in_array($code, $this->successCodes());
    }

    /**
     * Success HTTP status codes
     *
     * @return array
     */
    protected function successCodes(): array
    {
        return [200, 201, 202];
    }

    /**
     * Create response with data and message
     *
     * @param mixed|null $data
     * @param string     $message
     * @return JsonResponse
     */
    public function createResponse(mixed $data = null, string $message = "Created successfully") : JsonResponse
    {
        return $this->apiResponse($data, $message, 201);
    }

    /**
     * Delete response with data and message
     *
     * @param string $message
     * @return JsonResponse
     */
    public function deleteResponse(string $message = "Deleted successfully") : JsonResponse
    {
        return $this->apiResponse(message:$message);
    }

    /**
     * Update response with data and message
     *
     * @param mixed|null $data
     * @param string     $message
     * @return JsonResponse
     */
    public function updateResponse(mixed $data = null, string $message = "Updated successfully"): JsonResponse
    {
        return $this->apiResponse($data, $message, 202);
    }

    /**
     * Unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    public function unauthorizedResponse(string $message = "Unauthorized"): JsonResponse
    {
        return $this->apiResponse(null, $message, 401);
    }

    /**
     * Not Found response
     *
     * @param string $message
     * @return JsonResponse
     */
    public function notFoundResponse(string $message = "Resource not found"): JsonResponse
    {
        return $this->apiResponse(null, $message, 404);
    }

    /**
     * Unprocessable Entity response (Validation errors)
     *
     * @param array $errors
     * @return JsonResponse
     */
    public function apiValidation(array $errors = []): JsonResponse
    {
        return $this->apiResponse(null, "Validation errors", 422, $errors);
    }

    /**
     * Method Not Allowed response
     *
     * @param string $message
     * @return JsonResponse
     */
    public function methodNotAllowed(string $message = "Method not allowed"): JsonResponse
    {
        return $this->apiResponse(null, $message, 405);
    }

    /**
     * Forbidden response
     *
     * @param string $message
     * @return JsonResponse
     */
    public function forbiddenResponse(string $message = "Forbidden"): JsonResponse
    {
        return $this->apiResponse(null, $message, 403);
    }

    /**
     * Unknown error response
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public function unknownError(?string $message = "An unknown error occurred"): JsonResponse
    {
        return $this->apiResponse(null, $message, 400);
    }
}
