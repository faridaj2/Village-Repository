<?php

namespace App\Livewire\StrukturPemerintahan;

use App\Models\StrukturPemerintahan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Struktur Pemerintahan')]
class Index extends Component
{
    public $strukturList = [];

    public $editId = null;
    public $jabatan = '';
    public $nama = '';
    public $foto = '';

    public $parentId = '';
    public $showForm = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->strukturList = StrukturPemerintahan::orderBy('id')->get();
    }

    public function openForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $item = StrukturPemerintahan::findOrFail($id);
        $this->editId = $item->id;
        $this->jabatan = $item->jabatan;
        $this->nama = $item->nama;
        $this->foto = $item->foto ?? '';

        $this->parentId = $item->parent_id ?? '';
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'jabatan' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
        ]);

        $data = [
            'jabatan' => $this->jabatan,
            'nama' => $this->nama,
            'foto' => $this->foto ?: null,

            'parent_id' => $this->parentId ?: null,
        ];

        if ($this->editId) {
            StrukturPemerintahan::findOrFail($this->editId)->update($data);
            session()->flash('message', 'Data struktur berhasil diperbarui.');
        } else {
            StrukturPemerintahan::create($data);
            session()->flash('message', 'Data struktur berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadData();
    }

    public function delete($id)
    {
        StrukturPemerintahan::findOrFail($id)->delete();
        session()->flash('message', 'Data struktur berhasil dihapus.');
        $this->loadData();
    }

    private function resetForm()
    {
        $this->editId = null;
        $this->jabatan = '';
        $this->nama = '';
        $this->foto = '';

        $this->parentId = '';
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.struktur-pemerintahan.index');
    }
}
