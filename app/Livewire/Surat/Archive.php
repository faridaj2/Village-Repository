<?php

namespace App\Livewire\Surat;

use App\Models\Surat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Arsip Surat')]
class Archive extends Component
{
    #[Url]
    public $year = '';

    #[Url]
    public $filterJenis = '';

    public function mount()
    {
        if (!$this->year) {
            $this->year = (string) date('Y');
        }
    }

    public function render()
    {
        $years = Surat::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $query = Surat::with('penduduk', 'template')
            ->where('status', 'selesai')
            ->whereYear('created_at', $this->year)
            ->when($this->filterJenis, fn ($q) => $q->where('jenis_surat', $this->filterJenis))
            ->latest();

        return view('livewire.surat.archive', [
            'surats' => $query->get(),
            'years' => $years,
            'totalSurat' => $query->count(),
        ]);
    }
}
