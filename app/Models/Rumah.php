<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rumah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_rumah',
        'rt_id',
        'alamat',
        'posisi',
        'punya_mck',
        'kategori_rtlh',
        'kategori_rumah',
        'teraliri_listrik',
    ];

    protected function casts(): array
    {
        return [
            'punya_mck' => 'boolean',
            'teraliri_listrik' => 'boolean',
        ];
    }

    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    public function kartuKeluargas(): HasMany
    {
        return $this->hasMany(KartuKeluarga::class);
    }

    public function penduduks(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    public function hasKoordinat(): bool
    {
        return $this->posisi !== null && str_contains($this->posisi, ',');
    }

    public function getLatitudeAttribute(): ?float
    {
        if (!$this->posisi) return null;
        $parts = explode(',', $this->posisi);
        return count($parts) === 2 ? (float) trim($parts[0]) : null;
    }

    public function getLongitudeAttribute(): ?float
    {
        if (!$this->posisi) return null;
        $parts = explode(',', $this->posisi);
        return count($parts) === 2 ? (float) trim($parts[1]) : null;
    }
}
