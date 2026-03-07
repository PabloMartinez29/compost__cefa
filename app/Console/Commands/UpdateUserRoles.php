<?php

// Comando UpdateUserRoles — Actualiza roles de usuarios
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUserRoles extends Command
{
    // The name and signature of the console
    protected $signature = 'users:update-roles {--check : Solo verificar usuarios sin actualizar}';

    // The console command description
    protected $description = 'Actualizar roles de usuarios en la base de datos';

    // Execute the console command
    public function handle()
    {
        if ($this->option('check')) {
            $this->checkUsers();
            return;
        }

        $this->info('Actualizando roles de usuarios...');

        try {
            // Actualizar Juan Pablo como administrador usando DB::update
            $updated1 = DB::table('users')
                ->where('email', 'jpml11292006@gmail.com')
                ->update(['role' => 'admin']);
            
            if ($updated1) {
                $this->info("Usuario Juan Pablo actualizado como administrador");
            } else {
                $this->error("No se pudo actualizar Juan Pablo");
            }

            // Actualizar Ivan Perdomo como aprendiz usando DB::update
            $updated2 = DB::table('users')
                ->where('email', 'ivanperdomo@gmail.com')
                ->update(['role' => 'aprendiz']);
            
            if ($updated2) {
                $this->info("Usuario Ivan Perdomo actualizado como aprendiz");
            } else {
                $this->error("No se pudo actualizar Ivan Perdomo");
            }

            // Verificar los cambios
            $this->checkUsers();

            $this->info('Roles actualizados correctamente!');
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }

    private function checkUsers()
    {
        $this->info('Verificando usuarios en la base de datos...');
        $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
        
        foreach ($users as $user) {
            $role = $user->role ?? 'NULL';
            $this->line("ID: {$user->id} - {$user->name} - {$user->email} - Role: {$role}");
        }
    }
}
