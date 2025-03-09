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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->unsignedInteger('quantity');
            $table->string('name');
            $table->string('sku');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->decimal('price');
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('brand')->nullable();
            $table->string('range')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
