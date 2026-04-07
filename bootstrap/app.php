<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\NoCacheHeaders;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias de roles
        $middleware->alias([
            'role' => CheckRole::class,
        ]);
        // Middleware global web
        $middleware->web(append: [SetLocale::class, NoCacheHeaders::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Manejo de sesión expirada (419)
        $exceptions->renderable(function (TokenMismatchException $e, $request) {
            return redirect()->route('login')
                ->with('error', 'Tu sesión expiró. Por favor inicia sesión nuevamente.');
        });
    })->create();
