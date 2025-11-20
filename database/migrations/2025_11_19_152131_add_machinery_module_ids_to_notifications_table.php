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
            // Agregar campos para los módulos de maquinaria solo si no existen
            if (!Schema::hasColumn('notifications', 'maintenance_id')) {
                $table->unsignedBigInteger('maintenance_id')->nullable()->after('machinery_id');
            }
            if (!Schema::hasColumn('notifications', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->nullable()->after('maintenance_id');
            }
            if (!Schema::hasColumn('notifications', 'usage_control_id')) {
                $table->unsignedBigInteger('usage_control_id')->nullable()->after('supplier_id');
            }
        });
        
        // Agregar foreign keys
        try {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreign('maintenance_id')->references('id')->on('maintenances')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key ya existe, ignorar
        }
        
        try {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key ya existe, ignorar
        }
        
        try {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreign('usage_control_id')->references('id')->on('usage_controls')->onDelete('cascade');
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
            // Eliminar foreign keys
            $table->dropForeign(['maintenance_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['usage_control_id']);
            
            // Eliminar columnas
            $table->dropColumn(['maintenance_id', 'supplier_id', 'usage_control_id']);
        });
    }
};
