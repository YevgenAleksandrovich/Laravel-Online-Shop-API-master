<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

      /**
     * Send an error responce.
     *
     * @param string $message
     * @param mixed $data
     * @param integer $code
     * @return JsonResponse
     */
    public function sendError(string $message = 'Error.', $data = null, int $code = 200): JsonResponse
    {
        return $this->sendResponce($message, $data, $code, false);
    }

    /**
     * Send success responce.
     *
     * @param string $message
     * @param mixedd $data
     * @param integer $code
     * @return JsonResponse
     */
    public function sendSuccess(string $message = 'Success.', $data = null, int $code = 200): JsonResponse
    {
        return $this->sendResponce($message, $data, $code, true);
    }

    /**
     * Build responce.
     *
     * @param string $message
     * @param mixed $data
     * @param integer $code
     * @param boolean $success
     * @return JsonResponse
     */
    private function sendResponce(string $message, $data, int $code, bool $success): JsonResponse
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}