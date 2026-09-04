<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rw extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'boundary_geojson',
        'warna',
    ];

    public function rts(): HasMany
    {
        return $this->hasMany(Rt::class);
    }

    public function rumahs(): HasMany
    {
        return $this->hasManyThrough(Rumah::class, Rt::class);
    }
}
