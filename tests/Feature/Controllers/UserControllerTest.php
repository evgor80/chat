<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_stores_user()
    {
        $response = $this->postJson(
            '/api/v1/users',
            [
                'username' => 'test',
                'password' => '12345678',
                'confirm_password' => '12345678'
            ]
        );
        $response
            ->assertStatus(201)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('data.user', 'test')
                    ->where('message', 'Регистрация выполнена.')
                    ->where('status', 'success')
                    ->whereType('data.token', 'string')
            );
    }

    public function test_doesnt_store_if_validation_failed()
    {
        $response = $this->postJson(
            '/api/v1/users',
            [
                'username' => 'te',
                'password' => '12345678',
                'confirm_password' => '1234567'
            ]
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

    public function test_doesnt_store_and_returns_409_status_if_username_is_occupied()
    {
        User::factory()->create();
        $response = $this->postJson(
            '/api/v1/users',
            [
                'username' => 'test',
                'password' => '12345678',
            ],
        );
        $response
            ->assertStatus(409)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'Имя занято')
                    ->where('status', 'failed')
                    ->whereType('data', 'array')
            );
    }   

    public function test_logs_user_in()
    {
        User::factory()->create();
        $response = $this->postJson(
            '/api/v1/users/login',
            [
                'username' => 'test',
                'password' => '12345678',
            ]
        );
        $response
            ->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('data.user', 'test')
                    ->where('message', 'Вход выполнен.')
                    ->where('status', 'success')
                    ->whereType('data.token', 'string')
            );
    }

    public function test_doesnt_login_if_credentials_invalid()
    {

        User::factory()->create();
        $response = $this->postJson(
            '/api/v1/users/login',
            [
                'username' => 'test1',
                'password' => '12345678',
            ]
        );
        $response
            ->assertStatus(401)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'Unauthorised.')
                    ->where('status', 'failed')
                    ->where('data.error.0', 'Неверное имя пользователя или пароль')
            );
    }

    public function test_doesnt_login_if_validation_failed()
    {
        $response = $this->postJson(
            '/api/v1/users/login',
            [
                'username' => 'te',
                'password' => '1234567',
            ]
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

    public function test_confirms_username_is_available()
    {
        $response = $this->postJson(
            '/api/v1/users/name',
            ['name' => 'test']
        );
        $response
            ->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'Имя не занято.')
                    ->where('status', 'success')
                    ->where('data.name', 'test')
            );
    }

    public function test_returns_422_status_if_username_wasnt_provided(): void
    {
        $response = $this->postJson(
            '/api/v1/users/name',
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

    public function test_returns_409_status_if_username_is_occupied()
    {
        User::factory()->create();
        $response = $this->postJson(
            '/api/v1/users/name',
            ['name' => 'test']
        );
        $response
            ->assertStatus(409)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'Имя занято')
                    ->where('status', 'failed')
                    ->whereType('data', 'array')
            );

    }
}
