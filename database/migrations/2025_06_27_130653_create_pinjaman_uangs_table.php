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
        Schema::create('pinjaman_uangs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('tanggal');

            // kolom foreign key yang nullable
            $table->uuid('penggajian_karyawan_id')->nullable();
            $table->foreign('penggajian_karyawan_id')->references('id')->on('penggajian_karyawans')->onDelete('cascade');


            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('m_karyawans')->onDelete('cascade');


            $table->integer('nominal_peminjaman')->default(0);
            $table->integer('nominal_pengembalian')->default(0);
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_uangs');
    }
};
