<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {

            // Jadikan kolom-kolom ini nullable (DROP NOT NULL)
            DB::statement('ALTER TABLE penjualans ALTER COLUMN pabrik_id DROP NOT NULL');
            DB::statement('ALTER TABLE penjualans ALTER COLUMN sopir_id DROP NOT NULL');
            DB::statement('ALTER TABLE penjualans ALTER COLUMN do_type_id DROP NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {

            // Kembalikan jadi NOT NULL (akan gagal jika ada data NULL)
            DB::statement('ALTER TABLE penjualans ALTER COLUMN pabrik_id SET NOT NULL');
            DB::statement('ALTER TABLE penjualans ALTER COLUMN sopir_id SET NOT NULL');
            DB::statement('ALTER TABLE penjualans ALTER COLUMN do_type_id SET NOT NULL');
        });
    }
};
