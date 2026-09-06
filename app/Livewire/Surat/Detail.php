<?php

namespace App\Livewire\Surat;

use App\Models\Surat;
use App\Services\SuratService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Detail Surat')]
class Detail extends Component
{
    public ?Surat $surat = null;

    public function mount(Surat $surat)
    {
        $this->surat = $surat->load(['penduduk.kartuKeluarga', 'template', 'creator', 'printLogs.user']);
    }

    private function loadSurat()
    {
        $this->surat->load(['penduduk.kartuKeluarga', 'template', 'creator', 'printLogs.user']);
    }

    public function refresh()
    {
        $this->loadSurat();
    }

    public function setProses()
    {
        $this->surat->update(['status' => 'diproses']);
        $this->loadSurat();
        session()->flash('message', 'Status diubah ke Diproses.');
    }

    public function setSelesai()
    {
        // Re-render draft if needed
        if ($this->surat->template && $this->surat->penduduk) {
            $html = SuratService::renderFull($this->surat);
            $this->surat->update(['status' => 'selesai', 'draft_html' => $html]);
        } else {
            $this->surat->update(['status' => 'selesai']);
        }
        $this->loadSurat();
        session()->flash('message', 'Surat berhasil diselesaikan.');
    }

    public function setDraft()
    {
        $this->surat->update(['status' => 'draft']);
        $this->loadSurat();
        session()->flash('message', 'Surat dikembalikan ke draft.');
    }

    public function batalkan()
    {
        $this->surat->update(['status' => 'dibatalkan']);
        $this->loadSurat();
        session()->flash('message', 'Surat dibatalkan.');
    }

    public function downloadPdf()
    {
        if (!$this->surat->template || !$this->surat->penduduk) {
            session()->flash('error', 'Template atau data penduduk tidak lengkap.');
            return;
        }

        $path = SuratService::generatePdf($this->surat);
        SuratService::logPrint($this->surat, auth()->id(), 'pdf');

        $this->loadSurat();

        return response()->download(Storage::disk('local')->path($path), basename($path));
    }

    public function logPrint()
    {
        SuratService::logPrint($this->surat, auth()->id(), 'print');
        $this->loadSurat();
        session()->flash('message', 'Pencetakan tercatat.');
    }

    public function delete()
    {
        $this->surat->printLogs()->delete();
        SuratService::deletePdf($this->surat);
        $this->surat->delete();

        session()->flash('message', 'Surat berhasil dihapus.');
        return redirect()->route('surat.index');
    }

    public function render()
    {
        return view('livewire.surat.detail');
    }
}
