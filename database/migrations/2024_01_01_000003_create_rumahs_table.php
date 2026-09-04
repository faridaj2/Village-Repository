<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rumahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rumah')->nullable();
            $table->foreignId('dusun_id')->nullable()->constrained('dusuns')->nullOnDelete();
            $table->string('alamat')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('foto')->nullable();
            $table->string('status_kepemilikan')->nullable();
            $table->string('jenis_lantai')->nullable();
            $table->string('jenis_dinding')->nullable();
            $table->string('jenis_atap')->nullable();
            $table->string('sumber_air')->nullable();
            $table->boolean('punya_mck')->default(false);
            $table->string('sumber_listrik')->nullable();
            $table->enum('kategori_rtlh', ['layak', 'tidak_layak'])->nullable();
            $table->decimal('luas_bangunan', 8, 2)->nullable();
            $table->decimal('luas_tanah', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rumahs');
    }
};
