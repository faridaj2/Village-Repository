<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create rws table
        Schema::create('rws', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('boundary_geojson')->nullable();
            $table->string('warna', 7)->nullable();
            $table->timestamps();
        });

        // Create rts table
        Schema::create('rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rw_id')->constrained('rws')->cascadeOnDelete();
            $table->string('nama');
            $table->text('boundary_geojson')->nullable();
            $table->string('warna', 7)->nullable();
            $table->timestamps();
        });

        // Update rumahs: remove dusun_id, add rt_id
        Schema::table('rumahs', function (Blueprint $table) {
            $table->dropForeign(['dusun_id']);
            $table->dropColumn('dusun_id');
            $table->dropColumn(['rt', 'rw']);
            $table->foreignId('rt_id')->nullable()->after('kode_rumah')->constrained('rts')->nullOnDelete();
        });

        // Update fasilitas_umums: remove dusun_id, add rt_id
        Schema::table('fasilitas_umums', function (Blueprint $table) {
            $table->dropForeign(['dusun_id']);
            $table->dropColumn('dusun_id');
            $table->foreignId('rt_id')->nullable()->after('jenis')->constrained('rts')->nullOnDelete();
        });

        // Drop dusuns table
        Schema::dropIfExists('dusuns');
    }

    public function down(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            $table->dropForeign(['rt_id']);
            $table->dropColumn('rt_id');
        });

        Schema::table('fasilitas_umums', function (Blueprint $table) {
            $table->dropForeign(['rt_id']);
            $table->dropColumn('rt_id');
        });

        Schema::dropIfExists('rts');
        Schema::dropIfExists('rws');
    }
};
