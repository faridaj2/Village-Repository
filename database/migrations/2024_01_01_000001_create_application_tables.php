<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. RW ──
        Schema::create('rws', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('boundary_geojson')->nullable();
            $table->string('warna', 7)->nullable();
            $table->timestamps();
        });

        // ── 2. RT ──
        Schema::create('rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rw_id')->constrained('rws')->cascadeOnDelete();
            $table->string('nama');
            $table->text('boundary_geojson')->nullable();
            $table->string('warna', 7)->nullable();
            $table->timestamps();
        });

        // ── 3. Rumah ──
        Schema::create('rumahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rumah')->nullable();
            $table->foreignId('rt_id')->nullable()->constrained('rts')->nullOnDelete();
            $table->string('alamat')->nullable();
            $table->string('posisi')->nullable();
            $table->boolean('punya_mck')->default(false);
            $table->enum('kategori_rtlh', ['layak', 'tidak_layak'])->nullable();
            $table->enum('kategori_rumah', ['permanen', 'semi_permanen', 'darurat'])->nullable();
            $table->boolean('teraliri_listrik')->default(false);
            $table->timestamps();
        });

        // ── 4. Kartu Keluarga ──
        //    (kepala_keluarga_id FK ditambah setelah penduduks dibuat)
        Schema::create('kartu_keluargas', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 16)->unique();
            $table->unsignedBigInteger('kepala_keluarga_id')->nullable();
            $table->foreignId('rumah_id')->nullable()->constrained('rumahs')->nullOnDelete();
            $table->string('alamat')->nullable();
            $table->timestamps();
        });

        // ── 5. Penduduk ──
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('status_kawin')->nullable();
            $table->foreignId('kartu_keluarga_id')->nullable()->constrained('kartu_keluargas')->nullOnDelete();
            $table->json('status_khusus')->nullable();
            $table->enum('status_kk', ['suami', 'istri', 'anak'])->nullable();
            $table->enum('status', ['aktif', 'pindah', 'meninggal'])->default('aktif');
            $table->enum('jenis_penduduk', ['penduduk', 'pendatang'])->default('penduduk');
            $table->timestamps();
        });

        // ── 6. FK Kartu Keluarga → Penduduk ──
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->foreign('kepala_keluarga_id')
                  ->references('id')->on('penduduks')
                  ->nullOnDelete();
        });

        // ── 7. Surat ──
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tracking')->unique();
            $table->foreignId('penduduk_id')->constrained('penduduks')->cascadeOnDelete();
            $table->string('jenis_surat');
            $table->json('data_tambahan')->nullable();
            $table->enum('status', ['diajukan', 'diproses', 'selesai', 'ditolak'])->default('diajukan');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        // ── 8. Pengumuman ──
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->string('kategori')->nullable();
            $table->date('tanggal_publish');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        // ── 9. Mutasi Penduduk ──
        Schema::create('mutasi_penduduks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduks')->cascadeOnDelete();
            $table->enum('jenis_mutasi', ['lahir', 'meninggal', 'pindah', 'datang']);
            $table->date('tanggal_mutasi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // ── 10. Fasilitas Umum ──
        Schema::create('fasilitas_umums', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis');
            $table->foreignId('rt_id')->nullable()->constrained('rts')->nullOnDelete();
            $table->string('posisi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // ── 11. Desa Settings ──
        Schema::create('desa_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa')->nullable();
            $table->text('boundary_geojson')->nullable();
            $table->string('warna', 7)->nullable();
            $table->decimal('center_lat', 10, 7)->nullable();
            $table->decimal('center_lng', 10, 7)->nullable();
            $table->integer('zoom_level')->nullable();
            $table->timestamps();
        });

        // ── 12. Admin Settings ──
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        // ── 13. Struktur Pemerintahan ──
        Schema::create('struktur_pemerintahans', function (Blueprint $table) {
            $table->id();
            $table->string('jabatan');
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(0);
            $table->foreignId('parent_id')->nullable()
                  ->constrained('struktur_pemerintahans')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('struktur_pemerintahans');
        Schema::dropIfExists('admin_settings');
        Schema::dropIfExists('desa_settings');
        Schema::dropIfExists('fasilitas_umums');
        Schema::dropIfExists('mutasi_penduduks');
        Schema::dropIfExists('pengumumans');
        Schema::dropIfExists('surats');
        Schema::dropIfExists('kartu_keluargas');
        Schema::dropIfExists('penduduks');
        Schema::dropIfExists('rumahs');
        Schema::dropIfExists('rts');
        Schema::dropIfExists('rws');

        Schema::enableForeignKeyConstraints();
    }
};
