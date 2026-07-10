<?php

// Archivo principal de rutas web — COMPOST CEFA


use Illuminate\Support\Facades\Route;

// Rutas públicas y comunes
require __DIR__.'/common.php';

// Rutas de administrador
Route::middleware(['auth', 'role:admin'])->group(function () {
    $adminRoutes = glob(__DIR__.'/admin/*.php');
    foreach ($adminRoutes as $routeFile) {
        require $routeFile;
    }
});

// Rutas de aprendiz
Route::middleware(['auth', 'role:aprendiz'])->group(function () {
    $aprendizRoutes = glob(__DIR__.'/aprendiz/*.php');
    foreach ($aprendizRoutes as $routeFile) {
        require $routeFile;
    }
});

// Rutas de autenticación (Laravel Breeze)
require __DIR__.'/auth.php';

// Red de seguridad para rutas no encontradas (404) con validación de rol
Route::fallback(function () {
    if (auth()->check()) {
        $userRole = auth()->user()->role;
        $path = request()->path();

        if (str_starts_with($path, 'admin') && $userRole !== 'admin') {
            return redirect()->route('aprendiz.dashboard')->with('unauthorized_access', true);
        }

        if (str_starts_with($path, 'aprendiz') && $userRole !== 'aprendiz') {
            return redirect()->route('dashboard.admin')->with('unauthorized_access', true);
        }
    }

    abort(404);
});
