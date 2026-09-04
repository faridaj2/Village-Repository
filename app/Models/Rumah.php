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
        'latitude',
        'longitude',
        'foto',
        'status_kepemilikan',
        'jenis_lantai',
        'jenis_dinding',
        'jenis_atap',
        'sumber_air',
        'punya_mck',
        'sumber_listrik',
        'teraliri_listrik',
        'kategori_rtlh',
        'kategori_rumah',
        'luas_bangunan',
        'luas_tanah',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'punya_mck' => 'boolean',
            'teraliri_listrik' => 'boolean',
            'luas_bangunan' => 'decimal:2',
            'luas_tanah' => 'decimal:2',
        ];
    }

    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class, 'rw_id');
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
        return $this->latitude !== null && $this->longitude !== null;
    }
}
