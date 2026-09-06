<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Surat Templates ──
        Schema::create('surat_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('jenis_surat');
            $table->text('header_html')->nullable();
            $table->longText('body_html');
            $table->text('footer_html')->nullable();
            $table->string('default_nomor_format')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ── 2. Update Surats ──
        Schema::table('surats', function (Blueprint $table) {
            $table->string('nomor_surat')->nullable()->after('id');
            $table->foreignId('surat_template_id')->nullable()->after('nomor_surat')->constrained('surat_templates')->nullOnDelete();
            $table->json('data_isian')->nullable()->after('data_tambahan');
            $table->string('nomor_manual')->nullable()->after('data_isian');
            $table->longText('draft_html')->nullable()->after('catatan_admin');
            $table->foreignId('created_by')->nullable()->after('draft_html')->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'diajukan', 'diproses', 'selesai', 'ditolak', 'dibatalkan'])->default('diajukan')->change();
        });

        // ── 3. Surat Print Logs ──
        Schema::create('surat_print_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('printed_at');
            $table->enum('format', ['pdf', 'print']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_print_logs');
        Schema::table('surats', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['surat_template_id']);
            $table->dropColumn(['nomor_surat', 'surat_template_id', 'data_isian', 'nomor_manual', 'draft_html', 'created_by']);
        });
        Schema::dropIfExists('surat_templates');
    }
};
