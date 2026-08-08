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
        Schema::create('activities', function (Blueprint $table) {
    $table->id();

    $table->foreignId('work_package_id')
        ->constrained('work_packages')
        ->cascadeOnDelete();

    $table->string('activity_code');

    $table->string('activity_name');

    $table->text('description')->nullable();

    $table->date('planned_start_date')->nullable();

    $table->date('planned_finish_date')->nullable();

    $table->date('actual_start_date')->nullable();

    $table->date('actual_finish_date')->nullable();

    $table->decimal('weight', 8, 2)->default(0);

    $table->string('status')->default('Draft');

    $table->timestamps();

    $table->unique(['work_package_id', 'activity_code']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
