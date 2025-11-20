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
        Schema::table('trackings', function (Blueprint $table) {
            $table->decimal('temp_internal', 5, 2)->nullable()->change();
            $table->time('temp_time')->nullable()->change();
            $table->decimal('temp_env', 5, 2)->nullable()->change();
            $table->decimal('hum_pile', 5, 2)->nullable()->change();
            $table->decimal('hum_env', 5, 2)->nullable()->change();
            $table->decimal('ph', 4, 2)->nullable()->change();
            $table->decimal('water', 10, 2)->nullable()->change();
            $table->decimal('lime', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trackings', function (Blueprint $table) {
            $table->decimal('temp_internal', 5, 2)->nullable(false)->change();
            $table->time('temp_time')->nullable(false)->change();
            $table->decimal('temp_env', 5, 2)->nullable(false)->change();
            $table->decimal('hum_pile', 5, 2)->nullable(false)->change();
            $table->decimal('hum_env', 5, 2)->nullable(false)->change();
            $table->decimal('ph', 4, 2)->nullable(false)->change();
            $table->decimal('water', 10, 2)->nullable(false)->change();
            $table->decimal('lime', 10, 2)->nullable(false)->change();
        });
    }
};
