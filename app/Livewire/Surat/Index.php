<?php

namespace App\Livewire\Surat;

use App\Models\Surat;
use App\Services\SuratService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Data Surat')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $filterStatus = '';

    #[Url]
    public $filterJenis = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function goto($id)
    {
        return redirect()->route('surat.detail', $id);
    }

    public function ubahStatus($id, $status)
    {
        $surat = Surat::findOrFail($id);
        $surat->update(['status' => $status]);
        session()->flash('message', "Status surat {$surat->nomor_display} diubah menjadi " . ucfirst($status) . '.');
    }

    public function delete($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->printLogs()->delete();
        SuratService::deletePdf($surat);
        $surat->delete();
        session()->flash('message', 'Surat berhasil dihapus.');
    }

    public function render()
    {
        $query = Surat::with('penduduk')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nomor_tracking', 'like', '%' . $this->search . '%')
                      ->orWhereHas('penduduk', function ($q2) {
                          $q2->where('nama', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterJenis, function ($query) {
                $query->where('jenis_surat', $this->filterJenis);
            })
            ->latest();

        return view('livewire.surat.index', [
            'surats' => $query->paginate(10),
        ]);
    }
}
