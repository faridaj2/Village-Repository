<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->foreign('kepala_keluarga_id')->references('id')->on('penduduks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->dropForeign(['kepala_keluarga_id']);
        });
    }
};
