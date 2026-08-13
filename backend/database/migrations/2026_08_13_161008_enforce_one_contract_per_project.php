<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enforce NG9 V1 Business Rule:
     * 1 Project = 1 Contract
     */
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->unique(
                'project_id',
                'contracts_project_id_unique'
            );
        });
    }

    /**
     * Reverse the database enforcement.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropUnique(
                'contracts_project_id_unique'
            );
        });
    }
};