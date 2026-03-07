<?php

// Provider AppServiceProvider — Registro de servicios de la aplicación
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // Register any application services
    public function register(): void
    {
        // Cargar helper de rutas de subida (funciona aunque en el servidor no se haya ejecutado composer dump-autoload)
        $helperPath = base_path('app/Helpers/upload_path.php');
        if (file_exists($helperPath)) {
            require_once $helperPath;
        }
    }

    // Bootstrap any application services
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
