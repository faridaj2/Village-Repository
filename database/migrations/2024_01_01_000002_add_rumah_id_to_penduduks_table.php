<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->foreignId('rumah_id')->nullable()->after('kartu_keluarga_id')->constrained('rumahs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->dropForeign(['rumah_id']);
            $table->dropColumn('rumah_id');
        });
    }
};
