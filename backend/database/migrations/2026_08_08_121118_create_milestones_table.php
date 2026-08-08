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
        Schema::create('milestones', function (Blueprint $table) {
    $table->id();

    $table->foreignId('contract_id')
        ->constrained('contracts')
        ->cascadeOnDelete();

    $table->unsignedInteger('milestone_no');

    $table->string('milestone_name');

    $table->text('description')->nullable();

    $table->date('planned_start_date')->nullable();

    $table->date('planned_finish_date')->nullable();

    $table->decimal('payment_percent', 8, 2)->default(0);

    $table->decimal('payment_amount', 15, 2)->default(0);

    $table->string('status')->default('Draft');

    $table->timestamps();

    $table->unique(['contract_id', 'milestone_no']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
