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
        Schema::create('periodes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('periode');
            $table->date('periode_mulai')->nullable();
            $table->date('periode_berakhir')->nullable();
            // $table->integer('stok');
            // $table->unsignedBigInteger('ops_id');
            // $table->foreign('ops_id')->references('id')->on('m_ops')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodes');
    }
};
