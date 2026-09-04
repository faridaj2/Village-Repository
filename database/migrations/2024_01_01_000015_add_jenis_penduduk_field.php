<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Revert status enum
        Schema::table('penduduks', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'pindah', 'meninggal'])->default('aktif')->change();
        });

        // Add new field
        Schema::table('penduduks', function (Blueprint $table) {
            $table->enum('jenis_penduduk', ['penduduk', 'pendatang'])->default('penduduk')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->dropColumn('jenis_penduduk');
        });
    }
};
