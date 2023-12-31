<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro(
            'success',
            function (mixed $result = [], mixed $message = 'ok', int $statusCode = 200) {
                return Response::json(
                    [
                        'success' => true,
                        'code' => $statusCode,
                        'message' => $message,
                        'result' => $result,
                        'error' => [],
                    ],
                    $statusCode
                );
            }
        );

        Response::macro(
            'error',
            function (string $message = 'Ошибка', int $statusCode = 422, mixed $error = []) {
                return Response::json(
                    [
                        'success' => false,
                        'code' => $statusCode,
                        'message' => $message,
                        'result' => [],
                        'error' => $error,
                    ],
                    $statusCode
                );
            }
        );
    }
}
