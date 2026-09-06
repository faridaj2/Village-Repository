<?php

namespace App\Livewire\Penduduk;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Detail Penduduk')]
class Detail extends Component
{
    public Penduduk $penduduk;

    public $showAssignKk = false;
    public $selectedKkId = '';

    public function mount(Penduduk|string|int $penduduk)
    {
        $this->penduduk = $penduduk instanceof Penduduk
            ? $penduduk->load(['kartuKeluarga.rumah.rt', 'rumah.rt.rw', 'mutasis'])
            : Penduduk::with(['kartuKeluarga.rumah.rt', 'rumah.rt.rw', 'mutasis'])->findOrFail($penduduk);
    }

    public function assignKk()
    {
        $this->validate([
            'selectedKkId' => 'required|exists:kartu_keluargas,id',
        ]);

        $this->penduduk->update(['kartu_keluarga_id' => $this->selectedKkId]);
        $this->penduduk->refresh();
        $this->showAssignKk = false;
        $this->selectedKkId = '';
        session()->flash('message', 'Penduduk berhasil diassign ke Kartu Keluarga.');
    }

    public function render()
    {
        $kkList = KartuKeluarga::whereNotIn('id', [$this->penduduk->kartu_keluarga_id])->get();

        return view('livewire.penduduk.detail', [
            'kkList' => $kkList,
        ]);
    }
}
