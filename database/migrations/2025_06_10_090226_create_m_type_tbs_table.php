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
        Schema::create('m_type_tbs', function (Blueprint $table) {
            $table->id();
            // $table->enum('type_tbs', ['TBS RUMAH', 'TBS LAHAN', 'TBS RAM']);
            $table->string('type_tbs', 20);
            $table->timestamps();
            // $table->softDeletes()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_type_tbs');
    }
};
