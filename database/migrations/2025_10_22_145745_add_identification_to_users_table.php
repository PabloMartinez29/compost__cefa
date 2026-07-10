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
        if (!Schema::hasColumn('users', 'identification')) {
            Schema::table('users', function (Blueprint $table) {
                // Avoid after()/change() so this works on SQLite (CI) and MySQL
                $table->string('identification')->nullable()->unique();
            });
        }

        // Asignar identificaciones únicas a usuarios existentes
        $users = \App\Models\User::query()->whereNull('identification')->get();
        foreach ($users as $user) {
            $user->identification = 'ID' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT);
            $user->save();
        }
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
