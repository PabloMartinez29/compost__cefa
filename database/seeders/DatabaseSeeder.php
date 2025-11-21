<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario Administrador
        User::firstOrCreate(
            ['email' => 'admin@cefa.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@cefa.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'identification' => 'ADMIN001',
            ]
        );

        // Crear usuario Aprendiz
        User::firstOrCreate(
            ['email' => 'aprendiz@cefa.com'],
            [
                'name' => 'Aprendiz',
                'email' => 'aprendiz@cefa.com',
                'password' => Hash::make('password123'),
                'role' => 'aprendiz',
                'identification' => 'APRENDIZ001',
            ]
        );
    }
}
