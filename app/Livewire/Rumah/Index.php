<?php

namespace App\Livewire\Rumah;

use App\Models\Rumah;
use App\Models\Rt;
use App\Models\Rw;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Data Rumah')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $filterKategori = '';

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
        $query = Rumah::query()
            ->with('rt.rw')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_rumah', 'like', '%' . $this->search . '%')
                      ->orWhere('alamat', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('kategori_rumah', $this->filterKategori);
            })
            ->when($this->filterRw, function ($query) {
                $query->whereHas('rt', function ($q) {
                    $q->where('rw_id', $this->filterRw);
                });
            })
            ->when($this->filterRt, function ($query) {
                $query->where('rt_id', $this->filterRt);
            })
            ->latest();

        return view('livewire.rumah.index', [
            'rumahs' => $query->paginate($this->perPage),
        ]);
    }

    public function delete($id)
    {
        $rumah = Rumah::findOrFail($id);
        $this->authorize('delete', $rumah);
        $rumah->delete();
        session()->flash('message', 'Data rumah berhasil dihapus.');
    }
}
