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

    public function test_confirms_name_is_available()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson(
            '/api/v1/rooms/name',
            ['name' => 'test']
        );
        $response
            ->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'Название не занято.')
                    ->where('status', 'success')
                    ->where('data.name', 'test')
            );
    }

    public function test_returns_422_status_if_name_wasnt_provided(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson(
            '/api/v1/rooms/name',
            []
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

    public function test_returns_409_status_if_name_is_occupied()
    {
        Room::factory()->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson(
            '/api/v1/rooms/name',
            ['name' => 'Main']
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

    public function test_shows_single_room(): void
    {
        Room::factory()->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->getJson('/api/v1/rooms/main');
        $response
            ->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'Чат найден.')
                    ->where('status', 'success')
                    ->where('data.room.name', 'Main')
            );
    }

    public function test_returns_404_code_if_room_wasnt_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->getJson('/api/v1/rooms/main');
        $response
            ->assertStatus(404)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'Not Found')
                    ->where('status', 'failed')
                    ->where('data.error.0', 'Запрошенный ресурс не найден')
            );
    }
}
