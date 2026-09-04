<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rt extends Model
{
    use HasFactory;

    protected $table = 'rts';

    protected $fillable = [
        'rw_id',
        'nama',
        'boundary_geojson',
        'warna',
    ];

    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class);
    }

    public function rumahs(): HasMany
    {
        return $this->hasMany(Rumah::class);
    }

    public function fasilitasUmums(): HasMany
    {
        return $this->hasMany(FasilitasUmum::class);
    }
}
