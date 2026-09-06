<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->longText('kode');
            $table->timestamps();
        });

        Schema::create('surat_manuals', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->longText('html');
            $table->string('paper_size', 10)->default('a4');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_manuals');
        Schema::dropIfExists('surat_blocks');
    }
};
