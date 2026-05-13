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
        Schema::create('pembelian_tbs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('periode_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');

            $table->date('tanggal_pembelian');

            $table->string('nama_customer', 50)->nullable();
            $table->unsignedBigInteger('tbs_type_id');
            $table->foreign('tbs_type_id')->references('id')->on('m_type_tbs')->onDelete('cascade');

            $table->integer('netto')->default(0);
            $table->integer('harga')->default(0);
            $table->integer('uang')->default(0);
            $table->integer('timbangan_first')->default(0);
            $table->integer('timbangan_second')->default(0);
            $table->integer('bruto')->default(0);

            $table->decimal('sortasi', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_tbs');
    }
};
