<?php

namespace App\Validation;

use App\Exceptions\InvalidWebSocketMessageException;
use Illuminate\Support\Facades\Validator;

class ChatConnectionValidation
{

    /**
     * Validate that message from client socket contains type of event and jwt-token
     * 
     * @param array $message
     * @return void
     * @throws \App\Exceptions\InvalidWebSocketMessageException
     */
    public static function preValidateMessage(array $message)
    {
        $validator = Validator::make($message, [
            'type' => 'required|string',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new InvalidWebSocketMessageException();
        }
    }

    /**
     * Validate that a user joinig a room provides all required information
     * 
     * @param array $message
     * @return void
     * @throws \App\Exceptions\InvalidWebSocketMessageException
     */
    public static function validateJoinMessageCompletness(array $message)
    {
        $validator = Validator::make($message, [
            'room' => 'required|string',
            'password' => 'present|string',
        ]);

        if ($validator->fails()) {
            throw new InvalidWebSocketMessageException();
        }
    }
}