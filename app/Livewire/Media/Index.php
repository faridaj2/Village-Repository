<?php

namespace App\Livewire\Media;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('File / Media')]
class Index extends Component
{
    use WithFileUploads;

    public $uploadFiles = [];

    public function saveUploads()
    {
        $this->validate([
            'uploadFiles' => 'required|array|max:10',
            'uploadFiles.*' => 'image|mimes:jpeg,png,webp,gif|max:10240',
        ], [
            'uploadFiles.required' => 'Pilih minimal satu gambar.',
            'uploadFiles.*.image' => 'File harus berupa gambar.',
            'uploadFiles.*.mimes' => 'Format harus jpeg, png, webp, atau gif.',
            'uploadFiles.*.max' => 'Ukuran maksimal 10 MB per gambar.',
        ]);

        $uploaded = 0;

        foreach ($this->uploadFiles as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = now()->format('YmdHis') . '-' . uniqid() . '.' . $ext;
            $path = Storage::disk('public')->putFileAs('uploads/images', $file, $filename);

            if (!$path) {
                continue;
            }

            $fullPath = Storage::disk('public')->path($path);
            $compressedSize = $this->compressImage($fullPath, $ext);

            Media::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $compressedSize,
                'uploaded_by' => auth()->id(),
            ]);

            $uploaded++;
        }

        $this->reset('uploadFiles');
        $this->dispatch('media-uploaded');
        session()->flash('message', "{$uploaded} gambar berhasil diunggah & dikompres.");
    }

    public function delete($id)
    {
        $media = Media::findOrFail($id);
        Storage::disk('public')->delete('uploads/images/' . $media->filename);
        $media->delete();
        session()->flash('message', 'Gambar berhasil dihapus.');
    }

    /**
     * Kompres & resize gambar menggunakan GD.
     * - Resize bila dimensi melebihi 1600px (sisi terpanjang)
     * - Re-encode JPEG/WebP kualitas 80, PNG kompresi 8
     * - GIF dipertahankan (hanya di-resize bila perlu)
     */
    private function compressImage(string $path, string $ext): int
    {
        $info = getimagesize($path);

        if ($info === false) {
            return (int) filesize($path);
        }

        [$width, $height] = $info;
        $maxDim = 1600;

        $src = match ($ext) {
            'png' => @imagecreatefrompng($path),
            'webp' => @imagecreatefromwebp($path),
            'gif' => @imagecreatefromgif($path),
            default => @imagecreatefromjpeg($path),
        };

        if (!$src) {
            return (int) filesize($path);
        }

        $resize = $width > $maxDim || $height > $maxDim;
        $newW = $width;
        $newH = $height;

        if ($resize) {
            $ratio = min($maxDim / $width, $maxDim / $height);
            $newW = (int) round($width * $ratio);
            $newH = (int) round($height * $ratio);
        }

        $dst = imagecreatetruecolor($newW, $newH);

        // Pertahankan transparansi untuk PNG & GIF
        if ($ext === 'png' || $ext === 'gif') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);

        match ($ext) {
            'png' => imagepng($dst, $path, 8),
            'webp' => imagewebp($dst, $path, 80),
            'gif' => imagegif($dst, $path),
            default => imagejpeg($dst, $path, 80),
        };

        $size = (int) filesize($path);

        imagedestroy($src);
        imagedestroy($dst);

        return $size;
    }

    public function render()
    {
        return view('livewire.media.index', [
            'files' => Media::with('uploader')->latest()->get(),
        ]);
    }
}