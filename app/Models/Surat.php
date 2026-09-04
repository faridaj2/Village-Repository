<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_tracking',
        'penduduk_id',
        'jenis_surat',
        'data_tambahan',
        'status',
        'catatan_admin',
        'file_pdf',
    ];

    protected function casts(): array
    {
        return [
            'data_tambahan' => 'array',
        ];
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

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
}
