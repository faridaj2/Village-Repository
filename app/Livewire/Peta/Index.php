<?php

namespace App\Livewire\Peta;

use App\Models\DesaSetting;
use App\Models\FasilitasUmum;
use App\Models\Rt;
use App\Models\Rumah;
use App\Models\Rw;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Peta Desa')]
class Index extends Component
{
    #[Url]
    public $filterRw = '';

    #[Url]
    public $filterRt = '';

    #[Url]
    public $filterRtlh = '';

    public $rumahs = [];
    public $fasilitas = [];
    public $rwList = [];
    public $rtList = [];
    public $boundaries = [];

    public function mount()
    {
        $this->rwList = Rw::orderBy('nama')->get();
        $this->loadData();
    }

    public function loadData()
    {
        $this->rtList = $this->filterRw
            ? Rt::where('rw_id', $this->filterRw)->orderBy('nama')->get()
            : [];

        $rumahQuery = Rumah::with('rt.rw')
            ->whereNotNull('posisi');

        if ($this->filterRw) {
            $rumahQuery->whereHas('rt', fn ($q) => $q->where('rw_id', $this->filterRw));
        }
        if ($this->filterRt) {
            $rumahQuery->where('rt_id', $this->filterRt);
        }
        if ($this->filterRtlh) {
            $rumahQuery->where('kategori_rtlh', $this->filterRtlh);
        }

        $this->rumahs = $rumahQuery->get()->map(fn ($r) => [
            'id' => $r->id,
            'lat' => $r->latitude,
            'lng' => $r->longitude,
            'kode' => $r->kode_rumah ?? '-',
            'alamat' => $r->alamat ?? '-',
            'rt' => $r->rt?->nama ?? '-',
            'rw' => $r->rt?->rw?->nama ?? '-',
            'rtlh' => $r->kategori_rtlh,
        ])->toArray();

        $fasilitasQuery = FasilitasUmum::with('rt.rw')
            ->whereNotNull('posisi');

        if ($this->filterRw) {
            $fasilitasQuery->whereHas('rt', fn ($q) => $q->where('rw_id', $this->filterRw));
        }
        if ($this->filterRt) {
            $fasilitasQuery->where('rt_id', $this->filterRt);
        }

        $this->fasilitas = $fasilitasQuery->get()->map(fn ($f) => [
            'id' => $f->id,
            'lat' => $f->latitude,
            'lng' => $f->longitude,
            'nama' => $f->nama,
            'jenis' => $f->jenis,
        ])->toArray();

        // Boundaries
        $this->boundaries = [];

        // Desa boundary
        $desa = DesaSetting::get();
        if ($desa && $desa->boundary_geojson) {
            $this->boundaries[] = [
                'nama' => $desa->nama_desa ?? 'Desa',
                'geojson' => $desa->boundary_geojson,
                'warna' => $desa->warna ?? '#ef4444',
                'tipe' => 'Desa',
            ];
        }

        $rwQuery = Rw::whereNotNull('boundary_geojson');
        if ($this->filterRw) {
            $rwQuery->where('id', $this->filterRw);
        }
        foreach ($rwQuery->get() as $rw) {
            $this->boundaries[] = [
                'nama' => $rw->nama,
                'geojson' => $rw->boundary_geojson,
                'warna' => $rw->warna ?? '#6366f1',
                'tipe' => 'RW',
            ];
        }

        $rtQuery = Rt::whereNotNull('boundary_geojson');
        if ($this->filterRw) {
            $rtQuery->where('rw_id', $this->filterRw);
        }
        if ($this->filterRt) {
            $rtQuery->where('id', $this->filterRt);
        }
        foreach ($rtQuery->get() as $rt) {
            $this->boundaries[] = [
                'nama' => $rt->nama,
                'geojson' => $rt->boundary_geojson,
                'warna' => $rt->warna ?? '#8b5cf6',
                'tipe' => 'RT',
            ];
        }
    }

    public function updatedFilterRw()
    {
        $this->filterRt = '';
        $this->loadData();
        $this->dispatch('map-data', rumahs: $this->rumahs, fasilitas: $this->fasilitas, boundaries: $this->boundaries);
    }

    public function updatedFilterRt()
    {
        $this->loadData();
        $this->dispatch('map-data', rumahs: $this->rumahs, fasilitas: $this->fasilitas, boundaries: $this->boundaries);
    }

    public function updatedFilterRtlh()
    {
        $this->loadData();
        $this->dispatch('map-data', rumahs: $this->rumahs, fasilitas: $this->fasilitas, boundaries: $this->boundaries);
    }

    public function render()
    {
        return view('livewire.peta.index');
    }
}
