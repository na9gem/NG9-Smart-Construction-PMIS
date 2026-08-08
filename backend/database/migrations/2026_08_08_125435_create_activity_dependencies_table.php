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
        Schema::create('activity_dependencies', function (Blueprint $table) {
    $table->id();

    $table->foreignId('predecessor_activity_id')
        ->constrained('activities')
        ->cascadeOnDelete();

    $table->foreignId('successor_activity_id')
        ->constrained('activities')
        ->cascadeOnDelete();

    $table->string('dependency_type')->default('FS');

    $table->integer('lag_days')->default(0);

    $table->text('description')->nullable();

    $table->timestamps();

    $table->unique([
        'predecessor_activity_id',
        'successor_activity_id',
        'dependency_type'
    ]);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_dependencies');
    }
};
