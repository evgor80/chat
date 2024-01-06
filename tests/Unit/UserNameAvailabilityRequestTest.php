<?php

namespace Tests\Unit;

use App\Http\Requests\UsernameAvailabilityRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\MakeValidator;

class UserNameAvailabilityRequestTest extends TestCase
{

    use MakeValidator;
    use DatabaseMigrations;

    private UsernameAvailabilityRequest $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new UsernameAvailabilityRequest();
    }

    public function test_passes_if_data_are_correct(): void
    {
        $validator = $this->makeValidator([
            'name' => 'user',
        ]);
        $this->assertTrue($validator->passes());
    }

    public function test_creates_error_if_name_is_mising(): void
    {
        $validator = $this->makeValidator(
            []
        );
        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }

    public function test_creates_error_if_name_is_not_a_string(): void
    {
        $validator = $this->makeValidator(
            [
                'name' => 1,
            ]
        );
        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }

    public function test_creates_error_if_name_is_short(): void
    {
        $validator = $this->makeValidator(
            [
                'name' => 'me',
            ]
        );
        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }

    public function test_creates_error_if_name_is_not_unique(): void
    {
        User::factory()->create();
        $validator = $this->makeValidator([
            'name' => 'test'
        ]);
        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }
}
