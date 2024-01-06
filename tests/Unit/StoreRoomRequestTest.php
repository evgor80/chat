<?php

namespace Tests\Unit;

use App\Http\Requests\StoreRoomRequest;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\MakeValidator;

class StoreRoomRequestTest extends TestCase
{
    use MakeValidator;
    use DatabaseMigrations;

    private $data;

    private StoreRoomRequest $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new StoreRoomRequest();

        $this->data = [
            'name' => 'test',
            'private' => true,
            'password' => '12345678',
            'confirm_password' => '12345678'

        ];
    }

    public function test_passes_if_data_are_correct(): void
    {
        $validator = $this->makeValidator($this->data);
        $this->assertTrue($validator->passes());
    }

    public function test_creates_error_if_name_is_mising(): void
    {
        $new_data = $this->data;
        $new_data['name'] = null;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }

    public function test_creates_error_if_name_is_not_a_string(): void
    {
        $new_data = $this->data;
        $new_data['name'] = 55;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }

    public function test_creates_error_if_name_is_short(): void
    {
        $new_data = $this->data;
        $new_data['name'] = 'me';

        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }

    public function test_creates_error_if_name_is_not_unique(): void
    {
        Room::factory()->create();
        $new_data = $this->data;
        $new_data['name'] = 'Main';
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }

    public function test_creates_error_if_private_is_mising(): void
    {
        $new_data = $this->data;
        $new_data['private'] = null;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('private', $validator->errors()->keys());
    }

    public function test_creates_error_if_private_is_not_boolean(): void
    {
        $new_data = $this->data;
        $new_data['private'] = 55;
        $validator = $this->makeValidator($new_data);
        $this->assertFalse($validator->passes());
        $this->assertContains('private', $validator->errors()->keys());
    }

    public function test_passes_if_password_is_not_required(): void
    {
        $new_data = $this->data;
        $new_data['private'] = false;
        $new_data['password'] = null;
        $new_data['confirm_password'] = null;
        $validator = $this->makeValidator($new_data);
        $this->assertTrue($validator->passes());
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