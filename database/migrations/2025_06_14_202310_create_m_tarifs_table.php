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
        Schema::create('m_tarifs', function (Blueprint $table) {
            $table->id();
            $table->integer('tarif_perkg');
            // $table->enum('type_karyawan', ['SOPIR', 'TKBM']);

            $table->unsignedBigInteger('type_karyawan_id');
            $table->foreign('type_karyawan_id')->references('id')->on('m_type_karyawans')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_tarifs');
    }
};
