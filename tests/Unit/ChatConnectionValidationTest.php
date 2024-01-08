<?php

namespace Tests\Unit;

use App\Exceptions\InvalidWebSocketMessageException;
use Tests\TestCase;
use App\Validation\ChatConnectionValidation;

class ChatConnectionValidationTest extends TestCase
{

    public function test_throws_exception_if_type_missing()
    {
        try {
            ChatConnectionValidation::preValidateMessage(['token' => 'test']);
            $this->fail("Exception wasn't thrown");
        } catch (InvalidWebSocketMessageException $e) {
            $this->assertInstanceOf(InvalidWebSocketMessageException::class, $e);
        }
    }

    public function test_throws_exception_if_token_missing()
    {
        try {
            ChatConnectionValidation::preValidateMessage(['type' => 'test']);
            $this->fail("Exception wasn't thrown");
        } catch (InvalidWebSocketMessageException $e) {
            $this->assertInstanceOf(InvalidWebSocketMessageException::class, $e);
        }
    }

    public function test_throws_exception_if_join_message_room_property_missing()
    {
        try {
            ChatConnectionValidation::validateJoinMessageCompletness(['token' => 'test', 'password' => '12345678']);
            $this->fail("Exception wasn't thrown");
        } catch (InvalidWebSocketMessageException $e) {
            $this->assertInstanceOf(InvalidWebSocketMessageException::class, $e);
        }
    }

    public function test_throws_exception_if_join_message_password_property_missing()
    {
        try {
            ChatConnectionValidation::validateJoinMessageCompletness(['token' => 'test', 'room' => 'Main']);
            $this->fail("Exception wasn't thrown");
        } catch (InvalidWebSocketMessageException $e) {
            $this->assertInstanceOf(InvalidWebSocketMessageException::class, $e);
        }
    }
}