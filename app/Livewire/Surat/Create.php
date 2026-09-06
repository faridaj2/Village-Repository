<?php

namespace App\Livewire\Surat;

use App\Models\Penduduk;
use App\Models\Surat;
use App\Models\SuratTemplate;
use App\Services\SuratService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Buat Surat')]
class Create extends Component
{
    public int $step = 1;

    // Step 1: Select template
    public ?int $selectedTemplateId = null;

    // Step 2: Select penduduk
    public $nik = '';
    public ?Penduduk $penduduk = null;
    public ?int $selectedPendudukId = null;

    // Step 3: Review & finalize
    #[Validate('nullable|string|max:100')]
    public $nomorUrutManual = '';

    #[Validate('nullable|date')]
    public $tanggalSurat = '';

    #[Validate('required|in:a4,f4')]
    public $paperSize = 'a4';

    public $dataIsian = [];
    public $draftHtml = '';
    public bool $isFinal = false;

    public function mount()
    {
        // Kosongkan default agar auto pakai now() saat load preview
        $this->tanggalSurat = '';
    }

    // ── Step Navigation ──

    public function nextStep()
    {
        if ($this->step === 1) {
            if (!$this->selectedTemplateId) {
                $this->addError('template', 'Pilih template terlebih dahulu.');
                return;
            }
            $this->step = 2;
        } elseif ($this->step === 2) {
            if (!$this->selectedPendudukId) {
                $this->addError('penduduk', 'Pilih penduduk terlebih dahulu.');
                return;
            }
            $this->loadPreview();
            $this->step = 3;
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function selectTemplate($id)
    {
        $this->selectedTemplateId = $id;
    }

    // ── Step 2: Search penduduk ──

    public function updatedNik()
    {
        if (strlen($this->nik) >= 4) {
            $this->penduduk = Penduduk::where('nik', 'like', '%' . $this->nik . '%')
                ->orWhere('nama', 'like', '%' . $this->nik . '%')
                ->first();
        } else {
            $this->penduduk = null;
        }
    }

    public function selectPenduduk($id)
    {
        $this->selectedPendudukId = $id;
        $this->penduduk = Penduduk::find($id);
        $this->nik = $this->penduduk?->nik ?? '';
    }

    public function updatedNomorUrutManual()
    {
        if ($this->step === 3 && $this->selectedTemplateId && $this->selectedPendudukId) {
            $this->loadPreview();
        }
    }

    public function updatedTanggalSurat()
    {
        if ($this->step === 3 && $this->selectedTemplateId && $this->selectedPendudukId) {
            $this->loadPreview();
        }
    }

    // ── Step 3: Preview & Generate ──

    public function loadPreview()
    {
        $template = SuratTemplate::find($this->selectedTemplateId);
        $penduduk = Penduduk::find($this->selectedPendudukId);

        if (!$template || !$penduduk) return;

        $data = SuratService::buildDataFromPenduduk($penduduk);

        // Add surat data
        $nomor = SuratService::generateNomor($template, $this->nomorUrutManual);
        $tanggal = $this->tanggalSurat ? \Carbon\Carbon::parse($this->tanggalSurat) : now();
        $data['surat'] = [
            'nomor' => $nomor,
            'tanggal' => $tanggal->format('d'),
            'bulan' => '',
            'bulan_romawi' => '',
            'tahun' => $tanggal->format('Y'),
            'jenis' => ucfirst(str_replace('_', ' ', $template->jenis_surat)),
        ];

        // Fixbulan values
        $bulan = (int) $tanggal->format('m');
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $bulanRomawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        $data['surat']['bulan'] = $bulanIndo[$bulan] ?? '';
        $data['surat']['bulan_romawi'] = $bulanRomawi[$bulan] ?? '';

        $data['ttd'] = [
            'nama' => \App\Models\AdminSetting::get('ttd_kades_nama', ''),
            'jabatan' => \App\Models\AdminSetting::get('ttd_kades_jabatan', 'Kepala Desa'),
        ];

        $this->draftHtml = SuratService::renderTemplate($template, $data);
        $this->dataIsian = $data;
    }

    public function saveDraft()
    {
        $this->validate([
            'selectedTemplateId' => 'required',
            'selectedPendudukId' => 'required',
        ]);

        $template = SuratTemplate::find($this->selectedTemplateId);
        $nomor = SuratService::generateNomor($template, $this->nomorUrutManual);

        $surat = Surat::create([
            'nomor_surat' => $nomor,
            'nomor_tracking' => 'SRT-' . strtoupper(uniqid()),
            'surat_template_id' => $this->selectedTemplateId,
            'penduduk_id' => $this->selectedPendudukId,
            'jenis_surat' => $template->jenis_surat,
            'data_isian' => $this->dataIsian,
            'nomor_manual' => null,
            'status' => 'draft',
            'paper_size' => $this->paperSize,
            'draft_html' => $this->draftHtml,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Surat berhasil disimpan sebagai draft.');
        return redirect()->route('surat.detail', $surat->id);
    }

    public function finalize()
    {
        $this->validate([
            'selectedTemplateId' => 'required',
            'selectedPendudukId' => 'required',
        ]);

        $template = SuratTemplate::find($this->selectedTemplateId);
        $nomor = SuratService::generateNomor($template, $this->nomorUrutManual);

        $surat = Surat::create([
            'nomor_surat' => $nomor,
            'nomor_tracking' => 'SRT-' . strtoupper(uniqid()),
            'surat_template_id' => $this->selectedTemplateId,
            'penduduk_id' => $this->selectedPendudukId,
            'jenis_surat' => $template->jenis_surat,
            'data_isian' => $this->dataIsian,
            'nomor_manual' => null,
            'status' => 'selesai',
            'paper_size' => $this->paperSize,
            'draft_html' => $this->draftHtml,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Surat berhasil difinalisasi.');
        return redirect()->route('surat.detail', $surat->id);
    }

    public function render()
    {
        return view('livewire.surat.create', [
            'templates' => SuratTemplate::active()->get(),
            'penduduks' => Penduduk::when($this->nik && strlen($this->nik) >= 2, function ($q) {
                $q->where('nik', 'like', '%' . $this->nik . '%')
                  ->orWhere('nama', 'like', '%' . $this->nik . '%');
            })->limit(10)->get(),
        ]);
    }
}
