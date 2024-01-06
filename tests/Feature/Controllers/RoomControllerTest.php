<?php

namespace Tests\Feature\Controllers;

use App\Facades\JwToken;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected string $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->token = JwToken::generateJwt($user);
    }

    public function test_stores_new_chat_room(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson(
            '/api/v1/rooms',
            [
                'name' => 'Main',
                'private' => true,
                'password' => '12345678',
                'confirm_password' => '12345678'
            ],
        );
        $response
            ->assertStatus(201)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('data.room.name', 'Main')
                    ->where('message', 'Чат успешно создан')
                    ->where('status', 'success')
            );
    }

    public function test_doesnt_store_if_validation_failed()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson(
            '/api/v1/rooms',
            [
                'name' => 'Ma',
                'private' => true,
                'password' => '12345678',
                'confirm_password' => '1234567'
            ],
        );
        $response
            ->assertStatus(422)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'Недопустимые данные.')
                    ->where('status', 'failed')
                    ->whereType('data', 'array')
            );
    }

    public function test_doesnt_store_and_returns_409_status_if_name_is_occupied()
    {
        Room::factory()->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson(
            '/api/v1/rooms',
            [
                'name' => 'Main',
                'private' => true,
                'password' => '12345678',
                'confirm_password' => '1234567'
            ],
        );
        $response
            ->assertStatus(409)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'Название занято')
                    ->where('status', 'failed')
                    ->whereType('data', 'array')
            );
    }
}
