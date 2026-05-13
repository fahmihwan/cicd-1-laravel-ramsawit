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
        Schema::create('penggajian_karyawans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('penggajian_id');
            $table->foreign('penggajian_id')->references('id')->on('penggajians')->onDelete('cascade');

            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('m_karyawans')->onDelete('cascade');

            $table->integer('total_gaji')->nullable();
            $table->integer('pinjaman_saat_ini')->nullable();
            $table->integer('potongan_pinjaman')->nullable();
            $table->integer('sisa_pinjaman')->nullable();
            $table->integer('gaji_yang_diterima')->nullable();
            $table->boolean('is_gaji_dibayarkan')->nullable();

            $table->unsignedBigInteger('penanggung_jawab_id')->nullable();
            $table->foreign('penanggung_jawab_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian_karyawans');
    }
};
