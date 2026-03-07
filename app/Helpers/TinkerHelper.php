<?php

// Helper TinkerHelper — Funciones auxiliares para Tinker
if (!function_exists('admin')) {
    // Obtiene el usuario administrador
    function admin()
    {
        return \App\Models\User::where('role', 'admin')->first();
    }
}

if (!function_exists('aprendiz')) {
    // Obtiene el usuario aprendiz
    function aprendiz()
    {
        return \App\Models\User::where('role', 'aprendiz')->first();
    }
}

if (!function_exists('admin_user')) {
    // Obtiene el usuario administrador por email
    function admin_user()
    {
        return \App\Models\User::where('email', 'admin@cefa.com')->first();
    }
}

if (!function_exists('aprendiz_user')) {
    // Obtiene el usuario aprendiz por email
    function aprendiz_user()
    {
        return \App\Models\User::where('email', 'aprendiz@cefa.com')->first();
    }
}


