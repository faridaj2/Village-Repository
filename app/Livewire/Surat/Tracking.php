<?php

namespace App\Livewire\Surat;

use App\Models\Surat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Tracking Surat')]
class Tracking extends Component
{
    public $nomorTracking = '';
    public $surat = null;
    public $notFound = false;

    public function cari()
    {
        $this->surat = Surat::with('penduduk')
            ->where('nomor_tracking', $this->nomorTracking)
            ->first();
        $this->notFound = $this->surat === null;
    }

    public function render()
    {
        return view('livewire.surat.tracking');
    }
}
