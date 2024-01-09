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

    public function test_throws_exception_if_add_message_room_property_missing()
    {
        try {
            ChatConnectionValidation::validateChatMessageCompletness([
                'token' => 'test',
                'message' => 'Hello!'
            ]);
            $this->fail("Exception wasn't thrown");
        } catch (InvalidWebSocketMessageException $e) {
            $this->assertInstanceOf(InvalidWebSocketMessageException::class, $e);
        }
    }

    public function test_throws_exception_if_add_message_text_property_missing()
    {
        try {
            ChatConnectionValidation::validateChatMessageCompletness([
                'token' => 'test',
                'room' => 'Main',
            ]);
            $this->fail("Exception wasn't thrown");
        } catch (InvalidWebSocketMessageException $e) {
            $this->assertInstanceOf(InvalidWebSocketMessageException::class, $e);
        }
    }

    public function test_doesnt_throw_exception_if_all_add_message_properties_present()
    {
        try {
            $this->withoutExceptionHandling();
            ChatConnectionValidation::validateChatMessageCompletness([
                'token' => 'test',
                'room' => 'Main',
                'message' => 'Hello!',
            ]);
            $this->assertTrue(TRUE);
        } catch (\Exception $e) {
            $this->fail();
        }
    }

    public function test_throws_exception_if_room_name_missing_for_typing_event(): void
    {
        try {
            ChatConnectionValidation::validateTypingEventMessageCompletness([]);
            $this->fail("Exception wasn't thrown");
        } catch (InvalidWebSocketMessageException $e) {
            $this->assertInstanceOf(InvalidWebSocketMessageException::class, $e);
        }
    }
}