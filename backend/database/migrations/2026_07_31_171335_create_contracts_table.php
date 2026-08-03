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
        Schema::create('contracts', function (Blueprint $table) {

    $table->id();

    // 1 โครงการ = 1 สัญญา
    $table->foreignId('project_id')
          ->constrained()
          ->cascadeOnDelete();

    // ข้อมูลสัญญา
    $table->string('contract_no')->unique();
    $table->date('contract_date');

    // มูลค่าสัญญา
    $table->decimal('contract_amount',15,2);

    // ระยะเวลาก่อสร้าง
    $table->integer('contract_days');

    $table->date('start_date');
    $table->date('finish_date');

    // เงินประกัน
    $table->decimal('performance_bond',15,2)->nullable();

    // เงินประกันผลงาน
    $table->decimal('retention_percent',5,2)->default(5);

    // ค่าปรับ
    $table->decimal('penalty_per_day',15,2)->nullable();

    // วิธีจัดซื้อ
    $table->string('procurement_method')->nullable();

    // สถานะสัญญา
    $table->enum('status',[
        'Draft',
        'Active',
        'Completed',
        'Terminated'
    ])->default('Draft');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
