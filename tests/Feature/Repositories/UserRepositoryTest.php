<?php

namespace Tests\Feature\Repositories;

use App\DTO\UserDto;
use App\Interfaces\IUserRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    protected IUserRepository $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->repo = $this->app->make(IUserRepository::class);
    }

    public function test_saves_new_user()
    {
        $user = $this->repo->save(new UserDto('test11', '12345678'));
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('test11', $user->username);
        $this->assertTrue(Hash::check('12345678', $user->password));
    }

    public function test_finds_by_username()
    {
        User::factory()->create();
        $user = $this->repo->findByUsername('test');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('test', $user->username);
    }

    public function test_returns_null_if_user_wasnt_found_by_username()
    {
        $user = $this->repo->findByUsername('test');
        $this->assertNull($user);
    }
}