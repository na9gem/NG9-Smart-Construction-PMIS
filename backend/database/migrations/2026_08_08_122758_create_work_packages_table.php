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
        Schema::create('work_packages', function (Blueprint $table) {
    $table->id();

    $table->foreignId('milestone_id')
        ->constrained('milestones')
        ->cascadeOnDelete();

    $table->string('package_code');

    $table->string('package_name');

    $table->text('description')->nullable();

    $table->unsignedInteger('sequence_no')->default(1);

    $table->string('status')->default('Draft');

    $table->timestamps();

    $table->unique(['milestone_id', 'package_code']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_packages');
    }
};
