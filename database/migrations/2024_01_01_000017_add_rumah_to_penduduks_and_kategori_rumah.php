<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add rumah_id directly to penduduks (optional, independent of KK)
        Schema::table('penduduks', function (Blueprint $table) {
            $table->foreignId('rumah_id')->nullable()->after('kartu_keluarga_id')->constrained('rumahs')->nullOnDelete();
        });

        // Add kategori_rumah and teraliri_listrik to rumahs
        Schema::table('rumahs', function (Blueprint $table) {
            $table->enum('kategori_rumah', ['permanen', 'semi_permanen', 'darurat'])->nullable()->after('kategori_rtlh');
            $table->boolean('teraliri_listrik')->default(false)->after('sumber_listrik');
        });
    }

    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->dropForeign(['rumah_id']);
            $table->dropColumn('rumah_id');
        });

        Schema::table('rumahs', function (Blueprint $table) {
            $table->dropColumn(['kategori_rumah', 'teraliri_listrik']);
        });
    }
};
