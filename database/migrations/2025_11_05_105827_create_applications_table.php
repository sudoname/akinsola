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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cycle_id')->constrained()->cascadeOnDelete();
            $table->enum('track', ['secondary', 'university', 'polytechnic']);
            $table->enum('status', ['draft', 'submitted', 'under_review', 'decision_pending_release', 'approved', 'rejected', 'waitlisted'])->default('draft');
            $table->dateTime('submission_at')->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('score_academic', 5, 2)->nullable();
            $table->decimal('score_need', 5, 2)->nullable();
            $table->decimal('score_service', 5, 2)->nullable();
            $table->decimal('score_leadership', 5, 2)->nullable();
            $table->decimal('score_total', 6, 2)->nullable();
            $table->string('decision_reason_code')->nullable();
            $table->text('decision_note')->nullable();
            $table->dateTime('decision_set_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'cycle_id', 'track']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
