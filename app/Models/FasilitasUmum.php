<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FasilitasUmum extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis',
        'rt_id',
        'posisi',
        'foto',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [];
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

    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }
}
