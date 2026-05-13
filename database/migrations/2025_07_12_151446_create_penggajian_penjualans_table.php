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
        Schema::create('penggajian_penjualans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('penggajian_id');
            $table->foreign('penggajian_id')->references('id')->on('penggajians')->onDelete('cascade');
            $table->uuid('penjualan_id');
            $table->foreign('penjualan_id')->references('id')->on('penjualans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian_penjualans');
    }
};
