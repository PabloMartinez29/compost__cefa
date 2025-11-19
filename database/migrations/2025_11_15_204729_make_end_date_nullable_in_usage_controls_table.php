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
        // Usar el mismo nombre de tabla que en la migración original
        Schema::table('usage_Controls', function (Blueprint $table) {
            $table->dateTime('end_date')->nullable()->change();
            $table->integer('hours')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usage_Controls', function (Blueprint $table) {
            $table->dateTime('end_date')->nullable(false)->change();
            $table->integer('hours')->nullable(false)->change();
        });
    }
};
