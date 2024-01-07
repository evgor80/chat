<?php

namespace Tests\Unit;

use App\Http\Requests\RoomNameAvailabilityRequest;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\MakeValidator;

class RoomNameAvailabilityRequestTest extends TestCase
{

    use MakeValidator;
    use DatabaseMigrations;

    private RoomNameAvailabilityRequest $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new RoomNameAvailabilityRequest();
    }

    public function test_passes_if_data_are_correct(): void
    {
        $validator = $this->makeValidator([
            'name' => 'test',
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
        Room::factory()->create();
        $validator = $this->makeValidator([
            'name' => 'Main'
        ]);
        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }
}
