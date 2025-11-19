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
        Schema::table('Maintenances', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Maintenances', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });
    }
};
