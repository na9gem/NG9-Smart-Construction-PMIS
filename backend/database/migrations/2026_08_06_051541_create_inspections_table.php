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
        Schema::create('inspections', function (Blueprint $table) {
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

// วันที่ตรวจ
$table->date('inspection_date');

// ประเภทการตรวจ
$table->enum('inspection_type', [
    'Quality',
    'Safety',
    'Material',
    'Progress'
]);

// ตำแหน่งที่ตรวจ
$table->string('location')->nullable();

// ผลการตรวจ
$table->enum('result', [
    'Pass',
    'Fail'
])->default('Pass');

// ข้อสังเกต
$table->text('remark')->nullable();

// แนวทางแก้ไข
$table->text('corrective_action')->nullable();

// กำหนดแก้ไข
$table->date('due_date')->nullable();

// สถานะ
$table->enum('status', [
    'Open',
    'Closed'
])->default('Open');

$table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
