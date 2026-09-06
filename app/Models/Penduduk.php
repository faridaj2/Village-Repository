<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penduduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pendidikan_terakhir',
        'pekerjaan',
        'status_kawin',
        'kartu_keluarga_id',
        'status_khusus',
        'status_kk',
        'status',
        'jenis_penduduk',
        'rumah_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'status_khusus' => 'array',
        ];
    }

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class);
    }

    public function mutasis(): HasMany
    {
        return $this->hasMany(MutasiPenduduk::class);
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class);
    }

    public function rumah(): BelongsTo
    {
        return $this->belongsTo(Rumah::class);
    }

    public function tanpaKk(): bool
    {
        return $this->kartu_keluarga_id === null;
    }
}
