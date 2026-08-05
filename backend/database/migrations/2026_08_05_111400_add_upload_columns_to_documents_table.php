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
        Schema::table('documents', function (Blueprint $table) {
            $table->string('mime_type')->nullable()->after('file_size');

$table->text('remark')->nullable()->after('mime_type');

$table->foreignId('uploaded_by')
      ->nullable()
      ->after('remark')
      ->constrained('users')
      ->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('uploaded_by');

             $table->dropColumn([
             'mime_type',
             'remark',
            ]);

        });
    }
};
