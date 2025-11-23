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
        Schema::table('notifications', function (Blueprint $table) {
            // Agregar fertilizer_id como nullable
            if (!Schema::hasColumn('notifications', 'fertilizer_id')) {
                $table->unsignedBigInteger('fertilizer_id')->nullable()->after('usage_control_id');
            }
        });
        
        // Agregar foreign key para fertilizer_id
        try {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreign('fertilizer_id')->references('id')->on('fertilizers')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key ya existe, ignorar
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Eliminar foreign key
            try {
                $table->dropForeign(['fertilizer_id']);
            } catch (\Exception $e) {
                // Foreign key no existe, ignorar
            }
            
            // Eliminar fertilizer_id
            if (Schema::hasColumn('notifications', 'fertilizer_id')) {
                $table->dropColumn('fertilizer_id');
            }
        });
    }
};
