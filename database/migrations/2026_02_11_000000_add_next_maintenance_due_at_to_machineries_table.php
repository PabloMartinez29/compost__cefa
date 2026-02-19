<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machineries', function (Blueprint $table) {
            if (!Schema::hasColumn('machineries', 'next_maintenance_due_at')) {
                $table->dateTime('next_maintenance_due_at')->nullable()->after('maint_freq');
            }
        });
    }

    public function down(): void
    {
        Schema::table('machineries', function (Blueprint $table) {
            $table->dropColumn('next_maintenance_due_at');
        });
    }
};
