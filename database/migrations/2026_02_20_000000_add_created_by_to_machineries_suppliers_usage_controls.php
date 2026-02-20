<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('machineries', 'created_by')) {
            Schema::table('machineries', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable()->after('maint_freq');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (!Schema::hasColumn('suppliers', 'created_by')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable()->after('email');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (!Schema::hasColumn('usage_controls', 'created_by')) {
            Schema::table('usage_controls', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable()->after('status');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('machineries', function (Blueprint $table) {
            if (Schema::hasColumn('machineries', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
        });
        Schema::table('suppliers', function (Blueprint $table) {
            if (Schema::hasColumn('suppliers', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
        });
        Schema::table('usage_controls', function (Blueprint $table) {
            if (Schema::hasColumn('usage_controls', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
        });
    }
};
