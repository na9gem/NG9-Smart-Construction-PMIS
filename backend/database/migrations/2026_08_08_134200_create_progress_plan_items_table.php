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
        Schema::create('progress_plan_items', function (Blueprint $table) {
    $table->id();

    $table->foreignId('progress_plan_id')
        ->constrained('progress_plans')
        ->cascadeOnDelete();

    $table->foreignId('activity_id')
        ->constrained('activities')
        ->cascadeOnDelete();

    $table->date('plan_date');

    $table->decimal('planned_percent', 8, 2)->default(0);



    $table->timestamps();

    $table->unique([
        'progress_plan_id',
        'activity_id',
        'plan_date'
    ]);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_plan_items');
    }
};
