<?php

namespace Tests\Feature;

use App\Exceptions\FailedRequestException;
use App\Facades\JwToken;
use App\Http\Middleware\IsAuthenticated;
use App\Interfaces\IUserService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Tests\TestCase;

class IsAuthenticatedMiddlewareTest extends TestCase
{
    use DatabaseMigrations;

    public function test_handles_request_if_token_is_valid(): void
    {
        $request = new Request();
        $user = User::factory()->create();
        $token = JwToken::generateJwt($user);
        $request->headers->add(['Authorization' => "Bearer " . $token]);
        $middleware = new IsAuthenticated(app(IUserService::class));
        $response = $middleware->handle($request, function ($req) {

            return response('Success');
        });
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Success', $response->getContent());
    }
    public function test_throws_exception_if_there_is_no_token(): void
    {
        try {
            $request = new Request();
            $middleware = new IsAuthenticated(app(IUserService::class));
            $middleware->handle($request, function ($req) {

                return response('');
            });
        } catch (FailedRequestException $e) {
            $this->assertInstanceOf(FailedRequestException::class, $e);
            $this->assertSame(401, $e->code);
            $this->assertSame('Нет токена', $e->error);
            $this->assertSame('Требуется токен', $e->errorMessages->toArray()['error'][0]);
        }
    }

    public function test_throws_exception_if_user_is_invalid(): void
    {
        try {
            $request = new Request();
            $user = new User();
            $user->name = 'test';
            $user->id = 1;
            $token = JwToken::generateJwt($user);
            $request->headers->add(['Authorization' => "Bearer " . $token]);
            $middleware = new IsAuthenticated(app(IUserService::class));
            $middleware->handle($request, function ($req) {

                return response('');
            });
        } catch (FailedRequestException $e) {
            $this->assertInstanceOf(FailedRequestException::class, $e);
            $this->assertSame(401, $e->code);
            $this->assertSame('Ошибка токена', $e->error);
            $this->assertSame('Недопустимый или просроченный токен', $e->errorMessages->toArray()['error'][0]);
        }
    }

    public function test_throws_exception_if_token_is_expired(): void
    {
        try {
            $request = new Request();
            $user = User::factory()->create();
            Carbon::setTestNow(Carbon::now()->subMinutes(190));
            $token = JwToken::generateJwt($user);
            $request->headers->add(['Authorization' => "Bearer " . $token]);
            $middleware = new IsAuthenticated(app(IUserService::class));
            Carbon::setTestNow(Carbon::now());
            $middleware->handle($request, function ($req) {

                return response('');
            });
        } catch (FailedRequestException $e) {
            $this->assertInstanceOf(FailedRequestException::class, $e);
            $this->assertSame(401, $e->code);
            $this->assertSame('Ошибка токена', $e->error);
            $this->assertSame('Недопустимый или просроченный токен', $e->errorMessages->toArray()['error'][0]);
        }
    }
}
