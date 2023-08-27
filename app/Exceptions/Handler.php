<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\MissingScopeException;
use Psr\Log\LogLevel;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(
            function (Throwable $e) {
                //
            }
        );
    }

    public function report(Throwable $e)
    {
        parent::report($e);
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof MissingScopeException) {
            return $this->unauthenticated($request, $e);
        }
        $e = $this->prepareException($e);
        $class = get_class($e);
        switch ($class) {
            case AuthenticationException::class:
                return $this->unauthenticated($request, $e);
            case ValidationException::class:
                return response()->error(trans('message.error'), 422, [$e->errors()]);
            case NotFoundHttpException::class:
                return response()->error(trans('message.not_found'), 404);
            case AnyCodeMessageException::class:
                return response()->error($e->getMessage(), $e->getCode());
        }
        Log::build(
            [
                'driver' => 'single',
                'path' => storage_path('logs/error.log'),
            ]
        )->error($e->getMessage(), $e->getTrace());

        return response()->error(trans('message.error'), 500);
    }

    public function unauthenticated($request, AuthenticationException $e)
    {
        return response()->error(trans('auth.failed'), 401);
    }
}
