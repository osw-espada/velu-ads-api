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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('code')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('description')->nullable();
            $table->decimal('subtotal', 25, 2)->default("0.00");
            $table->decimal('total', 25, 2)->default("0.00");
            $table->string('currency')->nullable()->default("usd");
            $table->string('status')->nullable()->default("pending");
            $table->string('payment_id')->nullable();
            $table->string('payment_status')->nullable()->default("unpaid");
            $table->string('payment_object')->nullable()->default("checkout.session");
            $table->text('checkout_url')->nullable();
            $table->datetime('expired_at')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
