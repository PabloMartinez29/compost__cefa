<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cambiar el tipo de columna role de string a enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'aprendiz') NOT NULL DEFAULT 'aprendiz'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a string
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) NOT NULL DEFAULT 'aprendiz'");
    }
};
