<?php

// Middleware SetLocale — Configura el idioma de la aplicación
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    // Handle an incoming request
    public function handle(Request $request, Closure $next)
    {
        App::setLocale('es');
        return $next($request);
    }
}
