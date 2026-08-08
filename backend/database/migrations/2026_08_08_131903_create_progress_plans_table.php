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
        Schema::create('progress_plans', function (Blueprint $table) {
    $table->id();

    $table->foreignId('contract_id')
        ->constrained('contracts')
        ->cascadeOnDelete();

    $table->string('plan_name');

    $table->string('plan_type')->default('Baseline');

    $table->string('version')->default('01');

    $table->date('effective_date')->nullable();

    $table->foreignId('source_document_id')
        ->nullable()
        ->constrained('documents')
        ->nullOnDelete();

    $table->boolean('is_baseline')->default(false);

    $table->string('status')->default('Draft');

    $table->foreignId('created_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamps();

    $table->unique([
        'contract_id',
        'plan_type',
        'version'
    ]);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_plans');
    }
};
