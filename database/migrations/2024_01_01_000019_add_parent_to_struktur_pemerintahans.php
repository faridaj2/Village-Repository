<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('struktur_pemerintahans', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('urutan')->constrained('struktur_pemerintahans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('struktur_pemerintahans', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
