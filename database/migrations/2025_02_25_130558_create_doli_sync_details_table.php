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
        Schema::create('doli_sync_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("sync_id");
            $table->unsignedBigInteger("rowid");
            $table->string("ref");
            $table->string("type");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doli_sync_details');
    }
};
