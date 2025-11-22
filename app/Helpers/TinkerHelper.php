<?php

if (!function_exists('admin')) {
    /**
     * Obtiene el usuario administrador
     * 
     * @return \App\Models\User|null
     */
    function admin()
    {
        return \App\Models\User::where('role', 'admin')->first();
    }
}

if (!function_exists('aprendiz')) {
    /**
     * Obtiene el usuario aprendiz
     * 
     * @return \App\Models\User|null
     */
    function aprendiz()
    {
        return \App\Models\User::where('role', 'aprendiz')->first();
    }
}

if (!function_exists('admin_user')) {
    /**
     * Obtiene el usuario administrador por email
     * 
     * @return \App\Models\User|null
     */
    function admin_user()
    {
        return \App\Models\User::where('email', 'admin@cefa.com')->first();
    }
}

if (!function_exists('aprendiz_user')) {
    /**
     * Obtiene el usuario aprendiz por email
     * 
     * @return \App\Models\User|null
     */
    function aprendiz_user()
    {
        return \App\Models\User::where('email', 'aprendiz@cefa.com')->first();
    }
}


