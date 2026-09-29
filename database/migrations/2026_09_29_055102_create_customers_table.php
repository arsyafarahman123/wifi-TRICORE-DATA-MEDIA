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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('identity_number')->nullable();
            $table->text('address');
            $table->string('district')->default('Purwokerto Timur');
            $table->string('subdistrict')->nullable();
            $table->string('postal_code')->nullable();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->enum('status', ['pending', 'active', 'isolated', 'cancelled'])->default('pending');
            $table->date('installation_date')->nullable();
            $table->string('odp_code')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('registered_by')->default('online');
            $table->foreignId('partner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
