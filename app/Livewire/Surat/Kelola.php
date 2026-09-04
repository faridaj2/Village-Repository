<?php

namespace App\Livewire\Surat;

use App\Models\Surat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kelola Surat')]
class Kelola extends Component
{
    use WithPagination;

    #[Url]
    public $filterStatus = 'diajukan';

    public function proses($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->update(['status' => 'diproses']);
        session()->flash('message', 'Surat sedang diproses.');
    }

    public function selesai($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->update(['status' => 'selesai']);
        session()->flash('message', 'Surat telah selesai.');
    }

    public function tolak($id, $catatan = '')
    {
        $surat = Surat::findOrFail($id);
        $surat->update([
            'status' => 'ditolak',
            'catatan_admin' => $catatan,
        ]);
        session()->flash('message', 'Surat ditolak.');
    }

    public function render()
    {
        $query = Surat::with('penduduk')
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest();

        return view('livewire.surat.kelola', [
            'surats' => $query->paginate(10),
        ]);
    }
}
