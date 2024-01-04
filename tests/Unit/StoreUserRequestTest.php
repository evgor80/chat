<?php

namespace Tests\Unit;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\MakeValidator;

class StoreUserRequestTest extends TestCase
{
    use MakeValidator;
    use DatabaseMigrations;

    private $data;

    private StoreUserRequest $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new StoreUserRequest();

        $this->data = [
            'username' => 'user',
            'password' => '12345678',
            'confirm_password' => '12345678'

        ];
    }

    public function test_passes_if_data_are_correct(): void
    {
        $validator = $this->makeValidator($this->data);
        $this->assertTrue($validator->passes());
    }

    public function test_creates_error_if_username_is_mising(): void
    {
        $new_data = $this->data;
        $new_data['username'] = null;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('username', $validator->errors()->keys());
    }

    public function test_creates_error_if_username_is_not_a_string(): void
    {
        $new_data = $this->data;
        $new_data['username'] = 55;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('username', $validator->errors()->keys());
    }

    public function test_creates_error_if_username_is_short(): void
    {
        $new_data = $this->data;
        $new_data['username'] = 'me';
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('username', $validator->errors()->keys());
    }

    public function test_creates_error_if_username_is_not_unique(): void
    {
        $new_data = $this->data;
        $new_data['username'] = 'test';
        User::factory()->create();
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('username', $validator->errors()->keys());
    }

    public function test_creates_error_if_password_is_mising(): void
    {
        $new_data = $this->data;
        $new_data['password'] = null;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('password', $validator->errors()->keys());
    }

    public function test_creates_error_if_password_is_not_a_string(): void
    {
        $new_data = $this->data;
        $new_data['password'] = 12345678;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('password', $validator->errors()->keys());
    }

    public function test_creates_error_if_password_is_short(): void
    {
        $new_data = $this->data;
        $new_data['password'] = '1234567';

        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('password', $validator->errors()->keys());
    }

    public function test_creates_error_if_confirm_password_is_mising(): void
    {
        $new_data = $this->data;
        $new_data['confirm_password'] = null;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('confirm_password', $validator->errors()->keys());
    }

    public function test_creates_error_if_confirm_password_is_not_a_string(): void
    {
        $new_data = $this->data;
        $new_data['confirm_password'] = 12345678;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('confirm_password', $validator->errors()->keys());
    }

    public function test_creates_error_if_confirm_password_doesnt_match(): void
    {
        $new_data = $this->data;
        $new_data['confirm_password'] = '123456789';
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('confirm_password', $validator->errors()->keys());
    }
}