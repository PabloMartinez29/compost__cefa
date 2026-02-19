<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Vincula la entrada en bodega con el residuo orgánico para poder
     * actualizar el inventario cuando se edita el peso del residuo.
     */
    public function up(): void
    {
        Schema::table('warehouse_classification', function (Blueprint $table) {
            $table->unsignedBigInteger('organic_id')->nullable()->after('id');
            $table->foreign('organic_id')->references('id')->on('organics')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_classification', function (Blueprint $table) {
            $table->dropForeign(['organic_id']);
            $table->dropColumn('organic_id');
        });
    }
};
