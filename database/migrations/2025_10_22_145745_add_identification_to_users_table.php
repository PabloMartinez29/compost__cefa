<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('identification')->nullable()->after('email');
        });
        
        // Asignar identificaciones únicas a usuarios existentes
        $users = \App\Models\User::all();
        foreach ($users as $index => $user) {
            $user->identification = 'ID' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            $user->save();
        }
        
        // Ahora hacer la columna única
        Schema::table('users', function (Blueprint $table) {
            $table->string('identification')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('identification');
        });
    }
};
