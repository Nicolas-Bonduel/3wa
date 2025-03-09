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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('code')->nullable();
            $table->string('status')->default('pending');
            $table->text('comments')->nullable();
            $table->string('files_in')->nullable();
            $table->decimal('total');
            $table->decimal('amount');
            $table->decimal('tax_amount');
            $table->decimal('shipping_amount');
            $table->string('shipping_option');
            $table->decimal('coupon_amount')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->string('discount_description')->nullable();
            $table->integer('payment_id')->nullable();
            $table->integer('mail_id')->nullable();
            $table->string('mail_error')->nullable();
            $table->integer('seller_mail_id')->nullable();
            $table->string('seller_mail_error')->nullable();
            $table->integer('fk_dolibarr_order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
