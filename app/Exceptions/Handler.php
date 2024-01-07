<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\MessageBag;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws FailedRequestException
     */
    public function render($request, Throwable $e)
    {
        //send custom response if requested model wasn't found
        if ($e instanceof ModelNotFoundException) {
            throw new FailedRequestException('Not Found', new MessageBag(['error' => 'Запрошенный ресурс не найден']), 404);
        }

        return parent::render($request, $e);
    }
}
