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
        Schema::create('education_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('institution_name');
            $table->string('level_or_class')->nullable();
            $table->string('program')->nullable();
            $table->decimal('cgpa', 3, 2)->nullable();
            $table->string('jamb_reg_no')->nullable();
            $table->decimal('fees_amount', 10, 2)->nullable();
            $table->string('fees_doc_url')->nullable();
            $table->string('term_result_url')->nullable();
            $table->string('transcript_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_records');
    }
};
