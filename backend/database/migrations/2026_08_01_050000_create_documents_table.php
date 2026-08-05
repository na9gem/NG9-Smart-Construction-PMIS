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
    Schema::create('documents', function (Blueprint $table) {

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

        // ประเภทเอกสาร
        $table->string('document_type');

        // รหัสเอกสาร
        $table->string('document_no')->nullable();

        // ชื่อเอกสาร
        $table->string('document_name');

        // Revision
        $table->string('revision')->default('00');

        // วันที่เอกสาร
        $table->date('document_date')->nullable();

        // วันที่อัปโหลด
        $table->dateTime('uploaded_at')->nullable();

        // ไฟล์
        $table->string('file_name');

        $table->string('file_path');

        $table->string('file_extension')->nullable();

        $table->bigInteger('file_size')->nullable();

        // AI
        $table->longText('ai_summary')->nullable();

        $table->json('tags')->nullable();

        // สถานะ
        $table->enum('status',[
            'Draft',
            'Approved',
            'Rejected',
            'Archived'
        ])->default('Draft');

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
