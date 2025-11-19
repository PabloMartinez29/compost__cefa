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
            // Agregar machinery_id como nullable
            $table->unsignedBigInteger('machinery_id')->nullable()->after('composting_id');
            
            // Modificar el enum type para incluir 'maintenance_reminder'
            // Primero eliminar el enum existente y crear uno nuevo
            $table->dropColumn('type');
        });
        
        // Agregar la columna type con el nuevo enum
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('type', ['delete_request', 'edit_request', 'maintenance_reminder'])->after('composting_id');
        });
        
        // Agregar foreign key para machinery_id
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('machinery_id')->references('id')->on('machineries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Eliminar foreign key
            $table->dropForeign(['machinery_id']);
            
            // Eliminar machinery_id
            $table->dropColumn('machinery_id');
            
            // Restaurar el enum original
            $table->dropColumn('type');
        });
        
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('type', ['delete_request', 'edit_request'])->after('composting_id');
        });
    }
};
