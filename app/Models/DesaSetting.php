<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesaSetting extends Model
{
    protected $table = 'desa_settings';

    protected $fillable = [
        'nama_desa',
        'boundary_geojson',
        'warna',
        'center_lat',
        'center_lng',
        'zoom_level',
    ];

    protected function casts(): array
    {
        return [
            'center_lat' => 'decimal:7',
            'center_lng' => 'decimal:7',
        ];
    }

    public static function get(): ?self
    {
        return static::first();
    }

    public static function getOrCreate(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
