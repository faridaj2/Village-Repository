<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'nomor_tracking',
        'surat_template_id',
        'penduduk_id',
        'jenis_surat',
        'data_tambahan',
        'data_isian',
        'nomor_manual',
        'status',
        'paper_size',
        'catatan_admin',
        'draft_html',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'data_tambahan' => 'array',
            'data_isian' => 'array',
        ];
    }

    // ── Relationships ──

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(SuratTemplate::class, 'surat_template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function printLogs(): HasMany
    {
        return $this->hasMany(SuratPrintLog::class);
    }

    // ── Status checks ──

    public function isDiajukan(): bool
    {
        return $this->status === 'diajukan';
    }

    public function isDiproses(): bool
    {
        return $this->status === 'diproses';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    public function isDibatalkan(): bool
    {
        return $this->status === 'dibatalkan';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    // ── Helpers ──

    public function getNomorDisplayAttribute(): string
    {
        return $this->nomor_manual ?? $this->nomor_surat ?? '-';
    }
}
