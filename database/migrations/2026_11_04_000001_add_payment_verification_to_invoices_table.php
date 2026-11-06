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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('payment_reference');
            $table->timestamp('proof_uploaded_at')->nullable()->after('payment_proof');
            $table->text('rejection_reason')->nullable()->after('proof_uploaded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_proof', 'proof_uploaded_at', 'rejection_reason']);
        });
    }
};

