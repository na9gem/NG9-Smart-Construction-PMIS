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
        Schema::create('progress_reports', function (Blueprint $table) {
           $table->id();

// อ้างอิงโครงการ
$table->foreignId('project_id')
      ->constrained()
      ->cascadeOnDelete();

// อ้างอิงสัญญา
$table->foreignId('contract_id')
      ->nullable()
      ->constrained()
      ->nullOnDelete();

// วันที่รายงาน
$table->date('report_date');

// ความก้าวหน้า (%)
$table->decimal('progress_percent', 5, 2)->default(0);

// รายละเอียดงาน
$table->text('work_description')->nullable();

// ปัญหา
$table->text('problem')->nullable();

// แนวทางแก้ไข
$table->text('solution')->nullable();

// สภาพอากาศ
$table->string('weather')->nullable();

// จำนวนแรงงาน
$table->integer('manpower')->default(0);

// สถานะ
$table->enum('status', [
    'Draft',
    'Submitted',
    'Approved'
])->default('Draft');

$table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_reports');
    }
};
