<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega el campo created_by a la tabla fertilizers.
 *
 * Este campo es necesario para determinar qué usuario (aprendiz o admin)
 * creó el registro de abono, y así aplicar correctamente los permisos
 * de edición/eliminación en la vista del aprendiz.
 *
 * Sin este campo, el sistema usaba composting.created_by como proxy,
 * lo cual era incorrecto: un aprendiz podía registrar abono en una
 * pila creada por otro usuario y no poder editar/eliminar su propio registro.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fertilizers', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('notes');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('fertilizers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
