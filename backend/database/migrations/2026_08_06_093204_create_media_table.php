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
        Schema::create('media', function (Blueprint $table) {
            $table->id();

// Project
$table->foreignId('project_id')
      ->constrained()
      ->cascadeOnDelete();

// Contract
$table->foreignId('contract_id')
      ->nullable()
      ->constrained()
      ->nullOnDelete();

// Progress Report
$table->foreignId('progress_report_id')
      ->nullable()
      ->constrained()
      ->nullOnDelete();

// Inspection
$table->foreignId('inspection_id')
      ->nullable()
      ->constrained()
      ->nullOnDelete();

// ประเภทไฟล์
$table->enum('media_type', [
    'Photo',
    'Document',
    'Drawing',
    'Video',
    'Other'
]);

// ชื่อไฟล์
$table->string('file_name');

// ตำแหน่งไฟล์
$table->string('file_path');

// นามสกุลไฟล์
$table->string('file_extension',20);

// MIME Type
$table->string('mime_type',100);

// ขนาดไฟล์
$table->unsignedBigInteger('file_size');

// คำอธิบาย
$table->text('description')->nullable();

// AI Summary
$table->text('ai_summary')->nullable();

// วันที่อัปโหลด
$table->timestamp('uploaded_at');

$table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
