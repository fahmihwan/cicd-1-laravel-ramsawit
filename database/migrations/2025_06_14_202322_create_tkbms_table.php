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
        Schema::create('tkbms', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('m_karyawans')->onDelete('cascade');

            $table->uuid('penjualan_id');
            $table->foreign('penjualan_id')->references('id')->on('penjualans')->onDelete('cascade');

            $table->unsignedBigInteger('type_karyawan_id');
            $table->foreign('type_karyawan_id')->references('id')->on('m_type_karyawans')->onDelete('cascade');


            $table->string('tkbm_agg')->nullable();

            $table->boolean('is_gaji_dibayarkan')->default(false);

            $table->unsignedBigInteger('model_kerja_id')->nullable();
            $table->foreign('model_kerja_id')->references('id')->on('m_modelkerjas')->onDelete('cascade');

            $table->unsignedBigInteger('tarif_id')->nullable();
            $table->foreign('tarif_id')->references('id')->on('m_tarifs')->onDelete('cascade');

            $table->integer('tarif_sopir_borongan')->nullable();
            $table->integer('tarif_tkbm_borongan')->nullable();

            // $table->integer('total');
            $table->integer('jumlah_tkbm');
            $table->integer('jumlah_uang');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tkbms');
    }
};
