<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param array $result
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse(array $result, string $message, int $code = 200)
    {
        $response = [
            'status' => 'success',
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
}