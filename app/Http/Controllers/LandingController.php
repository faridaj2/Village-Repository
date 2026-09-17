<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\DesaSetting;
use App\Models\FasilitasUmum;
use App\Models\KartuKeluarga;
use App\Models\Media;
use App\Models\Penduduk;
use App\Models\Pengumuman;
use App\Models\Rumah;
use App\Models\StrukturPemerintahan;
use App\Models\Surat;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('landing.stats', 300, function () {
            return [
                'penduduk' => Penduduk::where('status', 'aktif')->count(),
                'kk'       => KartuKeluarga::count(),
                'surat'    => Surat::count(),
                'rumah'    => Rumah::count(),
            ];
        });

        $heroSliders = AdminSetting::get('hero_sliders', [
            ['img' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1920&h=1080&fit=crop', 'title' => '', 'sub' => ''],
            ['img' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1920&h=1080&fit=crop', 'title' => '', 'sub' => ''],
            ['img' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1920&h=1080&fit=crop', 'title' => '', 'sub' => ''],
        ]);

        $pengumumans = Pengumuman::published()
            ->latest('tanggal_publish')
            ->limit(3)
            ->get();

        $struktur = StrukturPemerintahan::query()
            ->orderByRaw('parent_id IS NULL DESC')
            ->orderBy('parent_id')
            ->orderBy('id')
            ->get();

        $fasilitas = FasilitasUmum::with('rt')
            ->orderBy('jenis')
            ->orderBy('nama')
            ->limit(12)
            ->get();

        $galeri = Media::query()
            ->where('mime_type', 'like', 'image/%')
            ->latest()
            ->limit(8)
            ->get();

        $desa = DesaSetting::get();

        $markers = $fasilitas
            ->filter(fn ($f) => $f->latitude && $f->longitude)
            ->map(fn ($f) => [
                'nama' => $f->nama,
                'jenis' => $f->jenis,
                'lat' => $f->latitude,
                'lng' => $f->longitude,
                'rt' => optional($f->rt)->nama,
            ])->values();

        return view('welcome', compact(
            'stats',
            'heroSliders',
            'pengumumans',
            'struktur',
            'fasilitas',
            'galeri',
            'desa',
            'markers'
        ));
    }
}
