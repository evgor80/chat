<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\MessageBag;

class FailedRequestException extends Exception
{
    /**
     * FailedRequestException constructor.
     *
     * @param string $userService
     * @param \Illuminate\Contracts\Support\MessageBag $errorMessages
     * @param int $code
     * @return void
     */
    public function __construct(
        public string $error,
        public MessageBag $errorMessages,
        public $code
    ) {
    }

    /**
     * Render custom exception
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        $response = [
            'status' => 'failed',
            'message' => $this->error,
        ];
        if (!$this->errorMessages->isEmpty()) {
            $response['data'] = $this->errorMessages;
        }

        return response()->json($response, $this->code);
    }
}
