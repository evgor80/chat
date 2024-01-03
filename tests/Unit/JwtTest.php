<?php

namespace Tests\Unit;

use App\Facades\JwToken;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JwtTest extends TestCase
{
    use DatabaseMigrations;
    public function test_generates_token()
    {
        $user = User::factory()->create();
        $token = JwToken::generateJwt($user);
        $this->assertIsString($token);
        $this->assertCount(3, explode('.', $token));
    }

    public function test_verifies_token()
    {
        $user = User::factory()->create();
        $token = JwToken::generateJwt($user);
        $decoded = JwToken::verifyJwt($token);
        $this->assertSame("test", $decoded->username);
        $this->assertSame(1, $decoded->id);
    }
}