<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'jenis_surat',
        'header_html',
        'body_html',
        'footer_html',
        'default_nomor_format',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
