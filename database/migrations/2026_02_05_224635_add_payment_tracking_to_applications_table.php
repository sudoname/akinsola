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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('payment_status')->default('not_applicable')->after('bank_account_type');
            // Payment statuses: not_applicable, pending, requirements_verified, sent, received

            $table->timestamp('payment_pending_at')->nullable()->after('payment_status');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_pending_at');
            $table->timestamp('payment_sent_at')->nullable()->after('payment_verified_at');
            $table->timestamp('payment_received_at')->nullable()->after('payment_sent_at');

            $table->text('payment_note')->nullable()->after('payment_received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_pending_at',
                'payment_verified_at',
                'payment_sent_at',
                'payment_received_at',
                'payment_note',
            ]);
        });
    }
};
