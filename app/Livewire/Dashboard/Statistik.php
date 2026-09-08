<?php

namespace App\Livewire\Dashboard;

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
    }

    public function render()
    {
        return view('livewire.dashboard.statistik');
    }
}
