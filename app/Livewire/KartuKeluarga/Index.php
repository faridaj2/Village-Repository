<?php

namespace App\Livewire\KartuKeluarga;

use App\Models\KartuKeluarga;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Data Kartu Keluarga')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = KartuKeluarga::query()
            ->with(['kepalaKeluarga', 'rumah', 'anggota'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('no_kk', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kepalaKeluarga', function ($q2) {
                          $q2->where('nama', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->latest();

        return view('livewire.kartu-keluarga.index', [
            'kartuKeluargas' => $query->paginate($this->perPage),
        ]);
    }

    public function delete($id)
    {
        $kk = KartuKeluarga::findOrFail($id);
        if ($kk->anggota()->count() > 0) {
            session()->flash('error', 'Tidak bisa menghapus KK yang masih memiliki anggota.');
            return;
        }
        $kk->delete();
        session()->flash('message', 'Data Kartu Keluarga berhasil dihapus.');
    }
}
