<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_manuals', function (Blueprint $table) {
            $table->foreignId('penduduk_id')->nullable()->after('html')->constrained('penduduks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('surat_manuals', function (Blueprint $table) {
            $table->dropForeign(['penduduk_id']);
            $table->dropColumn('penduduk_id');
        });
    }
};
