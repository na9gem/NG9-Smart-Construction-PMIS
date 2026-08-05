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

    // ข้อมูลสัญญา
    $table->string('contract_number')->nullable();

    // วงเงินตามสัญญา
    $table->decimal('contract_amount',15,2)->default(0);

    // ความก้าวหน้า (%)
    $table->decimal('progress_percent',5,2)->default(0);

    // สถานะ
    $table->enum('status',[
    'Draft',
    'Tender',
    'Construction',
    'Completed',
    'OnHold',
    'Cancelled'
])->default('Draft');

    // ระยะเวลา
    $table->date('planned_start_date')->nullable();
    $table->date('planned_finish_date')->nullable();

    // วันที่แล้วเสร็จจริง
     $table->date('actual_finish_date')->nullable();

    // ผู้รับผิดชอบ
    $table->foreignId('created_by')
      ->nullable()
      ->constrained('users')
      ->nullOnDelete();

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
