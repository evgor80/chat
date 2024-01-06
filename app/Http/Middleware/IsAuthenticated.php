<?php

namespace App\Http\Middleware;

use App\Interfaces\IUserService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\FailedRequestException;
use Exception;
use Illuminate\Support\MessageBag;
use App\Facades\JwToken;

class IsAuthenticated
{
    /**
     * Middleware constructor
     *
     * @param IUserService $userService
     * @return void
     */
    public function __construct(protected IUserService $userService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jwt = $request->bearerToken();
        if (!$jwt) {
            throw new FailedRequestException('Нет токена', new MessageBag(['error' => 'Требуется токен']), 401);
        }
        try {
            $decoded = JwToken::verifyJwt($jwt);
            $user = $this->userService->getUserById($decoded->id);
            $request->merge(['user' => $user]);
        } catch (Exception $e) {
            throw new FailedRequestException('Ошибка токена', new MessageBag(['error' => 'Недопустимый или просроченный токен']), 401);
        }

        return $next($request);
    }
}