<?php

namespace App\Livewire\FasilitasUmum;

use App\Models\FasilitasUmum;
use App\Models\Rt;
use App\Models\Rw;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Fasilitas Umum')]
class Index extends Component
{
    public $fasilitas = [];
    public $rwList = [];
    public $rtList = [];

    // Form fields
    public $editId = null;
    public $nama = '';
    public $jenis = '';
    public $selectedRw = '';
    public $rt_id = '';
    public $posisi = '';
    public $keterangan = '';
    public $showForm = false;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'jenis' => 'required|string|max:100',
        'rt_id' => 'nullable|exists:rts,id',
        'posisi' => 'nullable|string|max:50',
        'keterangan' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->rwList = Rw::orderBy('nama')->get();
        $this->loadData();
    }

    public function loadData()
    {
        $this->fasilitas = FasilitasUmum::with('rt.rw')->latest()->get();
    }

    public function updatedSelectedRw()
    {
        $this->rt_id = '';
        if ($this->selectedRw) {
            $this->rtList = Rt::where('rw_id', $this->selectedRw)->orderBy('nama')->get();
        } else {
            $this->rtList = [];
        }
    }

    public function openForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $f = FasilitasUmum::findOrFail($id);
        $this->editId = $f->id;
        $this->nama = $f->nama;
        $this->jenis = $f->jenis;
        $this->rt_id = $f->rt_id;
        $this->posisi = $f->posisi;
        $this->keterangan = $f->keterangan;

        if ($f->rt_id) {
            $rt = Rt::find($f->rt_id);
            if ($rt) {
                $this->selectedRw = $rt->rw_id;
                $this->rtList = Rt::where('rw_id', $this->selectedRw)->orderBy('nama')->get();
            }
        }

        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nama' => $this->nama,
            'jenis' => $this->jenis,
            'rt_id' => $this->rt_id ?: null,
            'posisi' => $this->posisi ?: null,
            'keterangan' => $this->keterangan,
        ];

        if ($this->editId) {
            FasilitasUmum::findOrFail($this->editId)->update($data);
            session()->flash('message', 'Fasilitas berhasil diperbarui.');
        } else {
            FasilitasUmum::create($data);
            session()->flash('message', 'Fasilitas berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadData();
    }

    public function delete($id)
    {
        FasilitasUmum::findOrFail($id)->delete();
        session()->flash('message', 'Fasilitas berhasil dihapus.');
        $this->loadData();
    }

    public function cancelForm()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editId = null;
        $this->nama = '';
        $this->jenis = '';
        $this->selectedRw = '';
        $this->rt_id = '';
        $this->rtList = [];
        $this->posisi = '';
        $this->keterangan = '';
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.fasilitas-umum.index');
    }
}
