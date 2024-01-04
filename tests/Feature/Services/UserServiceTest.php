<?php

namespace Tests\Feature\Services;

use App\Interfaces\IUserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\DTO\UserDto;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;

    protected IUserService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(IUserService::class);
    }

    public function test_that_registers_new_user()
    {
        $result = $this->service->registerUser(new UserDto('test11', '12345678'));

        $this->assertEquals('test11', $result['user']);
        $this->assertIsString($result['token']);
        $this->assertCount(3, explode('.', $result['token']));
    }
}
