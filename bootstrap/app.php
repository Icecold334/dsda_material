<?php

use App\Http\Middleware\CheckPLT;
use App\Http\Middleware\CheckSudin;
use App\Http\Middleware\InjectSudin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'sudin' => CheckSudin::class,
            'inject.sudin' => InjectSudin::class,
            'plt' => CheckPLT::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
