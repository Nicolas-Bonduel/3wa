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
        Schema::create('doli_syncs', function (Blueprint $table) {
            $table->id();
            $table->text("error")->nullable();
            $table->text("warnings")->nullable();
            $table->unsignedInteger("up_amount")->nullable();
            $table->text("up_row_ids")->nullable();
            $table->unsignedInteger("add_amount")->nullable();
            $table->text("add_row_ids")->nullable();
            $table->unsignedInteger("del_amount")->nullable();
            $table->text("del_row_ids")->nullable();
            $table->text("logs")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doli_syncs');
    }
};
