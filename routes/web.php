<?php

// Archivo principal de rutas web — COMPOST CEFA
// Carga rutas segmentadas por rol: common, admin, aprendiz, auth

use Illuminate\Support\Facades\Route;

// Rutas públicas y comunes
require __DIR__.'/common.php';

// Rutas de administrador (auth + role:admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    $adminRoutes = glob(__DIR__.'/admin/*.php');
    foreach ($adminRoutes as $routeFile) {
        require $routeFile;
    }
});

// Rutas de aprendiz (auth + role:aprendiz)
Route::middleware(['auth', 'role:aprendiz'])->group(function () {
    $aprendizRoutes = glob(__DIR__.'/aprendiz/*.php');
    foreach ($aprendizRoutes as $routeFile) {
        require $routeFile;
    }
});

// Rutas de autenticación (Laravel Breeze)
require __DIR__.'/auth.php';
