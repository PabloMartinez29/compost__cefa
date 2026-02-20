<?php

/**
 * Ruta base donde se guardan las imágenes (residuos, pilas, maquinaria).
 * Si en .env está UPLOAD_PUBLIC_PATH apuntando a public_html, las imágenes
 * se guardan ahí y no hace falta copiarlas manualmente.
 */
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
