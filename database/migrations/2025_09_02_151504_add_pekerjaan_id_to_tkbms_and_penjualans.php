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
        Schema::table('tkbms', function (Blueprint $table) {
            $table->unsignedBigInteger('pekerjaan_lain_id')->nullable()->after('id');

            $table->foreign('pekerjaan_lain_id')
                ->references('id')
                ->on('m_pekerjaan_non_sawits')
                ->onDelete('set null');
        });

        // Tabel penjualans
        Schema::table('penjualans', function (Blueprint $table) {
            $table->unsignedBigInteger('pekerjaan_lain_id')->nullable()->after('id');

            $table->foreign('pekerjaan_lain_id')
                ->references('id')
                ->on('m_pekerjaan_non_sawits')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tkbms', function (Blueprint $table) {
            $table->dropForeign(['pekerjaan_lain_id']);
            $table->dropColumn('pekerjaan_lain_id');
        });

        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropForeign(['pekerjaan_lain_id']);
            $table->dropColumn('pekerjaan_lain_id');
        });
    }
};
