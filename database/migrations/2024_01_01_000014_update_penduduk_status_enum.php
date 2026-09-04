<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->enum('status', ['tetap', 'pendatang', 'pindah', 'meninggal'])->default('tetap')->change();
        });

        // Update existing 'aktif' to 'tetap'
        DB::table('penduduks')->where('status', 'aktif')->update(['status' => 'tetap']);
    }

    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'pindah', 'meninggal'])->default('aktif')->change();
        });
    }
};
