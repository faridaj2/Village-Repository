<?php

namespace App\Livewire\Penduduk;

use App\Models\Penduduk;
use App\Models\Rt;
use App\Models\Rw;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Data Penduduk')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $filterStatus = '';

    #[Url]
    public $filterTanpaKk = false;

    #[Url]
    public $filterRw = '';

    #[Url]
    public $filterRt = '';

    #[Url]
    public $perPage = 10;

    public $rwList = [];
    public $rtList = [];

    public function mount()
    {
        $this->rwList = Rw::orderBy('nama')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterRw()
    {
        $this->filterRt = '';
        $this->rtList = $this->filterRw
            ? Rt::where('rw_id', $this->filterRw)->orderBy('nama')->get()
            : [];
        $this->resetPage();
    }

    public function render()
    {
        $query = Penduduk::query()
            ->with('kartuKeluarga.rumah.rt.rw')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('nik', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterTanpaKk, function ($query) {
                $query->whereNull('kartu_keluarga_id');
            })
            ->when($this->filterRw, function ($query) {
                $query->whereHas('kartuKeluarga.rumah.rt', function ($q) {
                    $q->where('rw_id', $this->filterRw);
                });
            })
            ->when($this->filterRt, function ($query) {
                $query->whereHas('kartuKeluarga.rumah', function ($q) {
                    $q->where('rt_id', $this->filterRt);
                });
            })
            ->latest();

        return view('livewire.penduduk.index', [
            'penduduks' => $query->paginate($this->perPage),
        ]);
    }

    public function delete($id)
    {
        $penduduk = Penduduk::findOrFail($id);
        $this->authorize('delete', $penduduk);
        $penduduk->delete();
        session()->flash('message', 'Data penduduk berhasil dihapus.');
    }
}
