<?php

// Ruta base donde se guardan las imágenes
if (!function_exists('upload_base_path')) {
    function upload_base_path(string $path = ''): string
    {
        $base = config('filesystems.upload_public_path') ?: public_path();
        $base = rtrim($base, '/\\');

        if ($path === '') {
            return $base;
        }

        return $base . '/' . ltrim(str_replace('\\', '/', $path), '/');
    }
}

// URL para archivos en storage (usa /storage-file/
if (!function_exists('storage_asset')) {
    function storage_asset(string $path): string
    {
        return asset('storage-file/' . ltrim(str_replace('\\', '/', $path), '/'));
    }
}
