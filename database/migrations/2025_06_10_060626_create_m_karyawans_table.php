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
        Schema::create('m_karyawans', function (Blueprint $table) {
            $table->id();
            $table->string("nama", 50)->nullable();
            $table->unsignedBigInteger('main_type_karyawan_id');
            $table->foreign('main_type_karyawan_id')->references('id')->on('m_type_karyawans')->onDelete('cascade');

            // $table->enum('type_karyawan', ['SOPIR', 'TKBM']);


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_karyawans');
    }
};
