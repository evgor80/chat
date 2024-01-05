<?php

namespace Tests\Unit;

use App\Http\Requests\LoginUserRequest;
use Tests\TestCase;
use Tests\Traits\MakeValidator;

class LoginUserRequestTest extends TestCase
{

    use MakeValidator;

    private LoginUserRequest $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new LoginUserRequest();
    }

    public function test_passes_if_all_data_are_correct(): void
    {
        $validator = $this->makeValidator([
            'username' => 'test',
            'password' => '12345678'
        ]);
        $this->assertTrue($validator->passes());
    }

    public function test_creates_error_if_username_is_mising(): void
    {
        $validator = $this->makeValidator(
            [
                'password' => '12345678'
            ]
        );
        $this->assertFalse($validator->passes());
        $this->assertContains('username', $validator->errors()->keys());
    }

    public function test_creates_error_if_username_is_not_a_string(): void
    {
        $validator = $this->makeValidator(
            [
                'username' => 1,
                'password' => '12345678'
            ]
        );
        $this->assertFalse($validator->passes());
        $this->assertContains('username', $validator->errors()->keys());
    }

    public function test_creates_error_if_username_is_short(): void
    {
        $validator = $this->makeValidator(
            [
                'username' => 'me',
                'password' => '12345678'
            ]
        );
        $this->assertFalse($validator->passes());
        $this->assertContains('username', $validator->errors()->keys());
    }

    public function test_creates_error_if_password_is_mising(): void
    {
        $validator = $this->makeValidator(
            [
                'username' => 'test',
            ]
        );
        $this->assertFalse($validator->passes());
        $this->assertContains('password', $validator->errors()->keys());
    }

    public function test_creates_error_if_password_is_not_a_string(): void
    {
        $validator = $this->makeValidator(
            [
                'username' => 'test',
                'password' => 125
            ]
        );
        $this->assertFalse($validator->passes());
        $this->assertContains('password', $validator->errors()->keys());
    }

    public function test_creates_error_if_password_is_short(): void
    {
        $validator = $this->makeValidator(
            [
                'username' => 'test',
                'password' => '1234567'
            ]
        );
        $this->assertFalse($validator->passes());
        $this->assertContains('password', $validator->errors()->keys());
    }
}