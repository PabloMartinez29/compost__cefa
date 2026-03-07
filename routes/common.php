<?php

// Rutas públicas y comunes (sin autenticación requerida)

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Middleware\SetLocale;

// Función para servir archivos de storage (workaround para Laragon/Windows)
$serveStorageFile = function (string $path) {
    $path = str_replace(['../', '..\\'], '', $path);
    $fullPath = null;

    if (Storage::disk('public')->exists($path)) {
        $fullPath = Storage::disk('public')->path($path);
    }

    if (!$fullPath && function_exists('upload_base_path')) {
        $publicPath = upload_base_path('storage/' . $path);
        if (file_exists($publicPath) && is_file($publicPath)) {
            $fullPath = $publicPath;
        }
    }

    if (!$fullPath || !file_exists($fullPath)) {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">'
            . '<rect width="100" height="100" fill="#e5e7eb"/>'
            . '<path d="M30 35h40v30H30z" fill="#9ca3af"/>'
            . '<circle cx="50" cy="42" r="8" fill="#6b7280"/>'
            . '<path d="M32 65l8-10 6 8 12-14 14 16H32z" fill="#6b7280"/>'
            . '</svg>';
        return response($svg, 200, ['Content-Type' => 'image/svg+xml', 'Cache-Control' => 'public, max-age=3600']);
    }

    $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
    return response()->file($fullPath, ['Content-Type' => $mime]);
};

// Rutas de servicio de archivos de storage
Route::get('/storage/{path}', $serveStorageFile)->where('path', '.*')->name('storage.serve');
Route::get('/storage-file/{path}', $serveStorageFile)->where('path', '.*')->name('storage.file');

// Páginas públicas
Route::get('/', function () {
    return view('welcome');
})->middleware(SetLocale::class);

Route::get('/developers', function () {
    return view('developers');
})->name('developers');

Route::get('/soporte', [App\Http\Controllers\SupportController::class, 'index'])->name('soporte');
Route::get('/manual/{type}', [App\Http\Controllers\SupportController::class, 'viewManual'])
    ->where('type', 'aprendiz|administrador')
    ->name('manual.view');

// Manual técnico solo para admin
Route::get('/manual/tecnico', [App\Http\Controllers\SupportController::class, 'viewManual'])
    ->defaults('type', 'tecnico')
    ->middleware(['auth', 'role:admin'])
    ->name('manual.view.tecnico');

// Redirección del dashboard según rol
Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard.admin');
        } else {
            return redirect()->route('aprendiz.dashboard');
        }
    }
    return redirect()->route('login');
})->middleware(['auth']);
