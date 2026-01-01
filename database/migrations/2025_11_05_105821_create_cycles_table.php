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
        Schema::create('cycles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('tracks_json')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('deadline_at');
            $table->dateTime('results_release_at');
            $table->decimal('budget_total', 12, 2)->nullable();
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->json('form_schema_json')->nullable();
            $table->dateTime('manual_published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycles');
    }
};
