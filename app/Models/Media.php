<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_name',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url('uploads/images/' . $this->filename);
    }

    public function getSizeLabelAttribute(): string
    {
        $size = $this->size;

        if ($size >= 1048576) {
            return round($size / 1048576, 1) . ' MB';
        }

        if ($size >= 1024) {
            return round($size / 1024, 1) . ' KB';
        }

        return $size . ' B';
    }
}