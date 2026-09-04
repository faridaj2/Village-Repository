<?php

namespace App\Livewire\Wilayah;

use App\Models\DesaSetting;
use App\Models\Rt;
use App\Models\Rw;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kelola Wilayah')]
class Index extends Component
{
    public $rwList = [];

    // Desa settings
    public $desaNama = '';
    public $desaBoundary = '';
    public $desaWarna = '#ef4444';
    public $showDesaForm = false;

    // RW form
    public $editRwId = null;
    public $rwNama = '';
    public $rwWarna = '#6366f1';
    public $rwBoundary = '';
    public $showRwForm = false;

    // RT form
    public $editRtId = null;
    public $rtRwId = '';
    public $rtNama = '';
    public $rtWarna = '#8b5cf6';
    public $rtBoundary = '';
    public $showRtForm = false;

    public function mount()
    {
        $this->loadData();
        $desa = DesaSetting::get();
        if ($desa) {
            $this->desaNama = $desa->nama_desa ?? '';
            $this->desaBoundary = $desa->boundary_geojson ?? '';
            $this->desaWarna = $desa->warna ?? '#ef4444';
        }
    }

    public function loadData()
    {
        $this->rwList = Rw::with('rts')->orderBy('nama')->get();
    }

    // RW methods
    public function openRwForm()
    {
        $this->resetRwForm();
        $this->showRwForm = true;
    }

    public function editRw($id)
    {
        $rw = Rw::findOrFail($id);
        $this->editRwId = $rw->id;
        $this->rwNama = $rw->nama;
        $this->rwWarna = $rw->warna ?? '#6366f1';
        $this->rwBoundary = $rw->boundary_geojson ?? '';
        $this->showRwForm = true;
    }

    public function saveRw()
    {
        $this->validate(['rwNama' => 'required|string|max:255']);

        $geojson = null;
        if (!empty($this->rwBoundary)) {
            $decoded = json_decode($this->rwBoundary);
            if (!$decoded) {
                $this->addError('rwBoundary', 'Format GeoJSON tidak valid.');
                return;
            }
            $geojson = $this->rwBoundary;
        }

        $data = ['nama' => $this->rwNama, 'warna' => $this->rwWarna, 'boundary_geojson' => $geojson];

        if ($this->editRwId) {
            Rw::findOrFail($this->editRwId)->update($data);
            session()->flash('message', 'RW berhasil diperbarui.');
        } else {
            Rw::create($data);
            session()->flash('message', 'RW berhasil ditambahkan.');
        }

        $this->resetRwForm();
        $this->loadData();
    }

    public function deleteRw($id)
    {
        Rw::findOrFail($id)->delete();
        session()->flash('message', 'RW berhasil dihapus.');
        $this->loadData();
    }

    private function resetRwForm()
    {
        $this->editRwId = null;
        $this->rwNama = '';
        $this->rwWarna = '#6366f1';
        $this->rwBoundary = '';
        $this->showRwForm = false;
    }

    // RT methods
    public function openRtForm($rwId)
    {
        $this->resetRtForm();
        $this->rtRwId = $rwId;
        $this->showRtForm = true;
    }

    public function editRt($id)
    {
        $rt = Rt::findOrFail($id);
        $this->editRtId = $rt->id;
        $this->rtRwId = $rt->rw_id;
        $this->rtNama = $rt->nama;
        $this->rtWarna = $rt->warna ?? '#8b5cf6';
        $this->rtBoundary = $rt->boundary_geojson ?? '';
        $this->showRtForm = true;
    }

    public function saveRt()
    {
        $this->validate([
            'rtRwId' => 'required|exists:rws,id',
            'rtNama' => 'required|string|max:255',
        ]);

        $geojson = null;
        if (!empty($this->rtBoundary)) {
            $decoded = json_decode($this->rtBoundary);
            if (!$decoded) {
                $this->addError('rtBoundary', 'Format GeoJSON tidak valid.');
                return;
            }
            $geojson = $this->rtBoundary;
        }

        $data = ['rw_id' => $this->rtRwId, 'nama' => $this->rtNama, 'warna' => $this->rtWarna, 'boundary_geojson' => $geojson];

        if ($this->editRtId) {
            Rt::findOrFail($this->editRtId)->update($data);
            session()->flash('message', 'RT berhasil diperbarui.');
        } else {
            Rt::create($data);
            session()->flash('message', 'RT berhasil ditambahkan.');
        }

        $this->resetRtForm();
        $this->loadData();
    }

    public function deleteRt($id)
    {
        Rt::findOrFail($id)->delete();
        session()->flash('message', 'RT berhasil dihapus.');
        $this->loadData();
    }

    private function resetRtForm()
    {
        $this->editRtId = null;
        $this->rtRwId = '';
        $this->rtNama = '';
        $this->rtWarna = '#8b5cf6';
        $this->rtBoundary = '';
        $this->showRtForm = false;
    }

    // Desa methods
    public function openDesaForm()
    {
        $this->showDesaForm = true;
    }

    public function saveDesa()
    {
        $geojson = null;
        if (!empty($this->desaBoundary)) {
            $decoded = json_decode($this->desaBoundary);
            if (!$decoded) {
                $this->addError('desaBoundary', 'Format GeoJSON tidak valid.');
                return;
            }
            $geojson = $this->desaBoundary;
        }

        DesaSetting::updateOrCreate(
            ['id' => 1],
            [
                'nama_desa' => $this->desaNama,
                'boundary_geojson' => $geojson,
                'warna' => $this->desaWarna,
            ]
        );

        $this->showDesaForm = false;
        session()->flash('message', 'Pengaturan desa berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.wilayah.index');
    }
}
