<?php

namespace App\Livewire\Dashboard;

use App\Models\DesaSetting;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Rumah;
use App\Models\Surat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Statistik extends Component
{
    public $totalPenduduk = 0;
    public $totalKk = 0;
    public $totalRumah = 0;
    public $suratMenunggu = 0;

    public $pendudukPerGender = [];
    public $pendudukPerStatus = [];
    public $rumahPerRtlh = [];
    public $suratPerStatus = [];

    public $pendudukPerUsia = [];
    public $usiaPerGender = [];
    public $pendudukPerPendidikan = [];
    public $pendudukPerPekerjaan = [];
    public $suratPerBulan = [];
    public $suratTerbaru = [];
    public $pendudukTerbaru = [];
    public $namaDesa = '';

    public function mount()
    {
        $this->totalPenduduk = Penduduk::where('status', 'aktif')->count();
        $this->totalKk = KartuKeluarga::count();
        $this->totalRumah = Rumah::where(function ($query) {
            $query->whereIn('id', function ($subQuery) {
                $subQuery->select('rumah_id')->from('kartu_keluargas')->whereNotNull('rumah_id');
            })->orWhereIn('id', function ($subQuery) {
                $subQuery->select('rumah_id')->from('penduduks')->whereNotNull('rumah_id');
            });
        })->count();
        $this->suratMenunggu = Surat::whereIn('status', ['diajukan', 'diproses'])->count();

        $this->pendudukPerGender = Penduduk::where('status', 'aktif')
            ->selectRaw('jenis_kelamin, count(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin')
            ->toArray();

        $this->pendudukPerStatus = Penduduk::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $this->rumahPerRtlh = Rumah::whereNotNull('kategori_rtlh')
            ->selectRaw('kategori_rtlh, count(*) as total')
            ->groupBy('kategori_rtlh')
            ->pluck('total', 'kategori_rtlh')
            ->toArray();

        $this->suratPerStatus = Surat::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Penduduk per kelompok usia
        $pendudukWithAge = Penduduk::where('status', 'aktif')
            ->whereNotNull('tanggal_lahir')
            ->selectRaw("
                CASE
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 0 AND 4 THEN '0-4'
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 5 AND 14 THEN '5-14'
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 15 AND 24 THEN '15-24'
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 25 AND 44 THEN '25-44'
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 45 AND 64 THEN '45-64'
                    ELSE '65+'
                END as kelompok_usia, count(*) as total")
            ->groupBy('kelompok_usia')
            ->pluck('total', 'kelompok_usia')
            ->toArray();

        $urutanUsia = ['0-4', '5-14', '15-24', '25-44', '45-64', '65+'];
        $this->pendudukPerUsia = [];
        foreach ($urutanUsia as $kelompok) {
            $this->pendudukPerUsia[$kelompok] = (int) ($pendudukWithAge[$kelompok] ?? 0);
        }

        // Piramida usia per jenis kelamin (untuk chart dashboard)
        $usiaGenderRaw = Penduduk::where('status', 'aktif')
            ->whereNotNull('tanggal_lahir')
            ->selectRaw("
                CASE
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 0 AND 4 THEN '0-4'
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 5 AND 14 THEN '5-14'
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 15 AND 24 THEN '15-24'
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 25 AND 44 THEN '25-44'
                    WHEN CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 45 AND 64 THEN '45-64'
                    ELSE '65+'
                END as kelompok_usia, jenis_kelamin, count(*) as total")
            ->groupBy('kelompok_usia', 'jenis_kelamin')
            ->get();
        $this->usiaPerGender = [];
        foreach ($urutanUsia as $kelompok) {
            $this->usiaPerGender[$kelompok] = ['L' => 0, 'P' => 0];
        }
        foreach ($usiaGenderRaw as $row) {
            $this->usiaPerGender[$row->kelompok_usia][$row->jenis_kelamin] = (int) $row->total;
        }

        // Penduduk per pendidikan terakhir
        $this->pendudukPerPendidikan = Penduduk::where('status', 'aktif')
            ->whereNotNull('pendidikan_terakhir')
            ->where('pendidikan_terakhir', '!=', '')
            ->selectRaw('pendidikan_terakhir, count(*) as total')
            ->groupBy('pendidikan_terakhir')
            ->orderByDesc('total')
            ->pluck('total', 'pendidikan_terakhir')
            ->toArray();

        // Penduduk per pekerjaan (top 5 + lainnya)
        $allPekerjaan = Penduduk::where('status', 'aktif')
            ->whereNotNull('pekerjaan')
            ->where('pekerjaan', '!=', '')
            ->selectRaw('pekerjaan, count(*) as total')
            ->groupBy('pekerjaan')
            ->orderByDesc('total')
            ->pluck('total', 'pekerjaan')
            ->toArray();

        $top5 = array_slice($allPekerjaan, 0, 5, true);
        $sisa = array_sum(array_slice($allPekerjaan, 5));
        if ($sisa > 0) {
            $top5['Lainnya'] = $sisa;
        }
        $this->pendudukPerPekerjaan = $top5;

        // Surat per bulan (6 bulan terakhir)
        $suratRaw = Surat::where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("strftime('%Y-%m', created_at) as bulan, count(*) as total")
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $this->suratPerBulan = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $this->suratPerBulan[$key] = (int) ($suratRaw[$key] ?? 0);
        }

        // Surat terbaru (5 pengajuan terakhir)
        $this->suratTerbaru = Surat::with('penduduk')
            ->latest()
            ->limit(5)
            ->get();

        // Penduduk terbaru (5 data terakhir)
        $this->pendudukTerbaru = Penduduk::latest()
            ->limit(5)
            ->get();

        // Nama desa
        $this->namaDesa = DesaSetting::get()?->nama_desa ?? 'Desa';
    }

    public function render()
    {
        return view('livewire.dashboard.statistik');
    }
}
