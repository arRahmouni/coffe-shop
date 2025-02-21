<?php

use Illuminate\Foundation\Application;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/v1'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
            SubstituteBindings::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception, $request) {

            if ($exception instanceof ValidationException) {
                return sendValidationResponse($exception->validator);
            }

            // Fallback to default exception handling
            return null;
        });
    })->create();
