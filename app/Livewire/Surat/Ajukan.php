<?php

namespace App\Livewire\Surat;

use App\Models\Penduduk;
use App\Models\Surat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Ajukan Surat')]
class Ajukan extends Component
{
    #[Validate('required|string|max:16')]
    public $nik = '';

    #[Validate('required|in:domisili,tidak_mampu,usaha,pengantar_ktp_kk')]
    public $jenis_surat = '';

    #[Validate('nullable|string|max:255')]
    public $keperluan = '';

    public $penduduk = null;
    public $submitted = false;
    public $nomorTracking = '';

    public function updatedNik()
    {
        $this->penduduk = Penduduk::where('nik', $this->nik)->first();
    }

    public function submit()
    {
        $this->validate();

        if (!$this->penduduk) {
            $this->addError('nik', 'NIK tidak ditemukan di sistem.');
            return;
        }

        $surat = Surat::create([
            'nomor_tracking' => 'SRT-' . strtoupper(uniqid()),
            'penduduk_id' => $this->penduduk->id,
            'jenis_surat' => $this->jenis_surat,
            'data_tambahan' => ['keperluan' => $this->keperluan],
            'status' => 'diajukan',
        ]);

        $this->nomorTracking = $surat->nomor_tracking;
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.surat.ajukan');
    }
}
