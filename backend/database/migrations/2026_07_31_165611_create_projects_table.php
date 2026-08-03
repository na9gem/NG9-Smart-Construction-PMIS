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
        Schema::create('projects', function (Blueprint $table) {

    $table->id();

    // ข้อมูลพื้นฐาน
    $table->string('project_code')->unique();
    $table->string('project_name');

    // หน่วยงาน
    $table->string('owner');
    $table->string('contractor')->nullable();
    $table->string('consultant')->nullable();

    // สถานที่
    $table->text('location')->nullable();

    // งบประมาณ
    $table->decimal('budget',15,2)->default(0);

    // สถานะ
    $table->enum('status',[
        'Planning',
        'Construction',
        'Completed',
        'Suspended'
    ])->default('Planning');

    // ระยะเวลา
    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();

    // ผู้รับผิดชอบ
    $table->foreignId('created_by')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
