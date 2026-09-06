<?php

namespace App\Livewire\Surat\Manual;

use App\Models\Penduduk;
use App\Models\SuratBlock;
use App\Models\SuratManual;
use App\Services\SuratService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Form Surat Manual')]
class Form extends Component
{
    public ?int $manualId = null;

    #[Validate('required|string|max:255')]
    public $nama = '';

    public $html = '';
    public $paperSize = 'a4';

    public $pendudukSearch = '';
    public ?int $selectedPendudukId = null;
    public ?Penduduk $selectedPenduduk = null;

    public bool $showPreview = false;

    public function mount(?int $id = null)
    {
        $this->manualId = $id;
        if ($id) {
            $manual = SuratManual::findOrFail($id);
            $this->nama = $manual->nama;
            $this->html = $manual->html;
            $this->paperSize = $manual->paper_size;
            $this->selectedPendudukId = $manual->penduduk_id;
            if ($this->selectedPendudukId) {
                $this->selectedPenduduk = Penduduk::find($this->selectedPendudukId);
            }
        }
    }

    public function insertBlock($blockId)
    {
        $block = SuratBlock::find($blockId);
        if ($block) {
            $this->html .= "\n" . $block->kode;
        }
    }

    public function insertVariable($var)
    {
        $this->html .= '{{' . $var . '}}';
    }

    public function updatedPendudukSearch()
    {
        // Placeholder: akan dimuat di render()
    }

    public function selectPenduduk($id)
    {
        $this->selectedPendudukId = $id;
        $this->selectedPenduduk = Penduduk::find($id);
        $this->pendudukSearch = $this->selectedPenduduk?->nik ?? '';
    }

    public function clearSelectedPenduduk()
    {
        $this->selectedPendudukId = null;
        $this->selectedPenduduk = null;
        $this->pendudukSearch = '';
    }

    public function renderManualHtml(): string
    {
        $html = $this->html;

        if ($this->selectedPenduduk) {
            $data = SuratService::buildDataFromPenduduk($this->selectedPenduduk);
            $data['surat'] = [
                'nomor' => '',
                'tanggal' => now()->format('d'),
                'bulan' => now()->translatedFormat('F'),
                'bulan_romawi' => SuratService::$bulanRomawi[(int) now()->format('m')],
                'tahun' => now()->format('Y'),
                'jenis' => '',
            ];
            $data['ttd'] = [
                'nama' => \App\Models\AdminSetting::get('ttd_kades_nama', ''),
                'jabatan' => \App\Models\AdminSetting::get('ttd_kades_jabatan', 'Kepala Desa'),
            ];

            $flat = [];
            $walk = function ($arr, $prefix = '') use (&$walk, &$flat) {
                foreach ($arr as $key => $value) {
                    if (is_array($value)) {
                        $walk($value, $prefix . $key . '.');
                    } else {
                        $flat[$prefix . $key] = (string) $value;
                    }
                }
            };
            $walk($data);

            foreach ($flat as $key => $value) {
                $html = str_replace('{{' . $key . '}}', $value, $html);
            }
        }

        return $html;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nama' => $this->nama,
            'html' => $this->html,
            'penduduk_id' => $this->selectedPendudukId,
            'paper_size' => $this->paperSize,
            'created_by' => auth()->id(),
        ];

        if ($this->manualId) {
            SuratManual::findOrFail($this->manualId)->update($data);
            session()->flash('message', 'Surat manual berhasil diupdate.');
        } else {
            SuratManual::create($data);
            session()->flash('message', 'Surat manual berhasil dibuat.');
        }

        return redirect()->route('surat.manual.index');
    }

    public function getVariableGroupsProperty(): array
    {
        return [
            ['color' => 'indigo', 'title' => 'Penduduk', 'vars' => [
                ['var' => 'penduduk.nama', 'example' => 'Ahmad 1'],
                ['var' => 'penduduk.nik', 'example' => '3200000000000001'],
                ['var' => 'penduduk.tempat_lahir', 'example' => 'Bandung'],
                ['var' => 'penduduk.tanggal_lahir_panjang', 'example' => '26 Oktober 2004'],
                ['var' => 'penduduk.jenis_kelamin', 'example' => 'Laki-laki'],
                ['var' => 'penduduk.agama', 'example' => 'Islam'],
                ['var' => 'penduduk.pekerjaan', 'example' => 'Wiraswasta'],
                ['var' => 'penduduk.umur', 'example' => '21 tahun'],
            ]],
            ['color' => 'emerald', 'title' => 'Kartu Keluarga', 'vars' => [
                ['var' => 'kk.no_kk', 'example' => '3200000000000001'],
                ['var' => 'kk.kepala_keluarga', 'example' => 'Ahmad 1'],
                ['var' => 'kk.alamat', 'example' => 'Jl. Contoh No. 1'],
            ]],
            ['color' => 'amber', 'title' => 'Lokasi', 'vars' => [
                ['var' => 'rumah.kode', 'example' => 'R001'],
                ['var' => 'rumah.alamat', 'example' => 'Jl. Contoh No. 1'],
                ['var' => 'rt', 'example' => 'RT 1'],
                ['var' => 'rw', 'example' => 'RW 1'],
            ]],
            ['color' => 'purple', 'title' => 'Desa & Surat', 'vars' => [
                ['var' => 'desa.nama', 'example' => 'Desa Waeleman'],
                ['var' => 'desa.kecamatan', 'example' => 'Kecamatan Waeleman'],
                ['var' => 'desa.kabupaten', 'example' => 'Kabupaten Ende'],
                ['var' => 'desa.provinsi', 'example' => 'NTT'],
                ['var' => 'surat.nomor', 'example' => '001/SRT/DOM/IX/2026'],
                ['var' => 'surat.tanggal', 'example' => '05'],
                ['var' => 'surat.bulan', 'example' => 'September'],
                ['var' => 'surat.bulan_romawi', 'example' => 'IX'],
                ['var' => 'surat.tahun', 'example' => '2026'],
                ['var' => 'ttd.nama', 'example' => 'Budi Santoso'],
                ['var' => 'ttd.jabatan', 'example' => 'Kepala Desa Waeleman'],
            ]],
        ];
    }

    public function render()
    {
        $penduduks = Penduduk::query()
            ->when($this->pendudukSearch && strlen($this->pendudukSearch) >= 2, function ($q) {
                $q->where('nik', 'like', '%' . $this->pendudukSearch . '%')
                  ->orWhere('nama', 'like', '%' . $this->pendudukSearch . '%');
            })
            ->limit(10)
            ->get();

        return view('livewire.surat.manual.form', [
            'blocks' => SuratBlock::orderBy('nama')->get(),
            'varGroups' => $this->variableGroups,
            'penduduks' => $penduduks,
        ]);
    }
}
