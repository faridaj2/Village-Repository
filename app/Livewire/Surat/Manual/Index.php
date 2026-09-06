<?php

namespace App\Livewire\Surat\Manual;

use App\Models\SuratManual;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Surat Manual')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        SuratManual::findOrFail($id)->delete();
        session()->flash('message', 'Surat manual dihapus.');
    }

    public function render()
    {
        $surats = SuratManual::query()
            ->when($this->search, function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.surat.manual.index', [
            'surats' => $surats,
        ]);
    }
}
