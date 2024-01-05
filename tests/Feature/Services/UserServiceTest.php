<?php

namespace Tests\Feature\Services;

use App\Interfaces\IUserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\DTO\UserDto;
use App\DTO\LoginDto;
use App\Models\User;
use App\Exceptions\FailedRequestException;

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

    public function test_logs_user_in()
    {
        User::factory()->create();
        $result = $this->service->login(new LoginDto('test', '12345678'));
        $this->assertEquals('test', $result['user']);
        $this->assertIsString($result['token']);
        $this->assertCount(3, explode('.', $result['token']));
    }

    public function test_throws_exception_if_credentials_invalid()
    {
        try {
            User::factory()->create();
            $this->withoutExceptionHandling();
            $this->service->login(new LoginDto('test1', '12345679'));
            $this->fail("Exception wasn't thrown");
        } catch (FailedRequestException $e) {
            $this->assertInstanceOf(FailedRequestException::class, $e);
            $this->assertSame("Unauthorised.", $e->error);
            $this->assertSame(401, $e->code);
            $this->assertSame('Неверное имя пользователя или пароль', $e->errorMessages->toArray()['error'][0]);
        }
    }
}
