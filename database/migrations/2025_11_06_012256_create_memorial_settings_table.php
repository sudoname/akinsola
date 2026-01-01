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
        Schema::create('memorial_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mother_photo')->nullable();
            $table->string('father_photo')->nullable();
            $table->timestamps();
        });

        // Create default record
        DB::table('memorial_settings')->insert([
            'id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorial_settings');
    }
};
