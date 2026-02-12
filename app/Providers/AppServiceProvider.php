<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Programar el comando de verificación de mantenimiento para ejecutarse diariamente a las 8:00 AM
        \Illuminate\Support\Facades\Schedule::command('machinery:check-maintenance')
            ->dailyAt('08:00')
            ->timezone('America/Bogota');

        // El día de la fecha del mantenimiento: enviar alerta a todos (admin y aprendiz) cada hora
        \Illuminate\Support\Facades\Schedule::command('machinery:send-maintenance-date-reminders')
            ->hourly()
            ->timezone('America/Bogota');
    }
}
