<?php

namespace App\Livewire\KartuKeluarga;

use App\Models\KartuKeluarga;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Detail Kartu Keluarga')]
class Detail extends Component
{
    public KartuKeluarga $kartuKeluarga;

    public function mount(KartuKeluarga|string|int $kartuKeluarga)
    {
        $this->kartuKeluarga = $kartuKeluarga instanceof KartuKeluarga
            ? $kartuKeluarga->load(['kepalaKeluarga', 'rumah.rt.rw', 'anggota'])
            : KartuKeluarga::with(['kepalaKeluarga', 'rumah.rt.rw', 'anggota'])->findOrFail($kartuKeluarga);
    }

    public function render()
    {
        return view('livewire.kartu-keluarga.detail');
    }
}
