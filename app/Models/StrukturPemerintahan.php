<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrukturPemerintahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jabatan',
        'nama',
        'foto',
        'parent_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(StrukturPemerintahan::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(StrukturPemerintahan::class, 'parent_id');
    }
}
