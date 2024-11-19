<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckProfile;
use App\Http\Middleware\EmployeeMiddleware;
use App\Http\Middleware\GroupAccessMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias(
            [
                'admin' => AdminMiddleware::class,
                'employee' => EmployeeMiddleware::class,
                'checkProfile' => CheckProfile::class,
                'groupAccess' => GroupAccessMiddleware::class,
            ]
        );

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
