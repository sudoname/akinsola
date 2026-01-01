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
        Schema::table('education_records', function (Blueprint $table) {
            $table->integer('year_of_study')->nullable()->after('program');
            $table->integer('graduation_year')->nullable()->after('cgpa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_records', function (Blueprint $table) {
            $table->dropColumn(['year_of_study', 'graduation_year']);
        });
    }
};
