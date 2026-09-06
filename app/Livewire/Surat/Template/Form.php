<?php

namespace App\Livewire\Surat\Template;

use App\Models\SuratTemplate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Form Template Surat')]
class Form extends Component
{
    public ?int $templateId = null;

    #[Validate('required|string|max:255')]
    public $nama = '';

    #[Validate('required|string|max:100')]
    public $slug = '';

    #[Validate('required|in:domisili,tidak_mampu,usaha,pengantar_ktp_kk,kelahiran,kematian,pindah,belum_menikah,custom')]
    public $jenis_surat = 'domisili';

    public $header_html = '';
    public $body_html = '';
    public $footer_html = '';
    public $default_nomor_format = '{nomor}/SRT/{jenis}/{bulan_romawi}/{tahun}';
    public bool $is_active = true;

    public bool $showPreview = true;

    public function mount(?int $id = null)
    {
        $this->templateId = $id;
        if ($id) {
            $template = SuratTemplate::findOrFail($id);
            $this->nama = $template->nama;
            $this->slug = $template->slug;
            $this->jenis_surat = $template->jenis_surat;
            $this->header_html = $template->header_html ?? '';
            $this->body_html = $template->body_html;
            $this->footer_html = $template->footer_html ?? '';
            $this->default_nomor_format = $template->default_nomor_format ?? '';
            $this->is_active = $template->is_active;
        }
    }

    public function updatedNama()
    {
        if (!$this->templateId) {
            $this->slug = \Illuminate\Support\Str::slug($this->nama);
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nama' => $this->nama,
            'slug' => $this->slug,
            'jenis_surat' => $this->jenis_surat,
            'header_html' => $this->header_html,
            'body_html' => $this->body_html,
            'footer_html' => $this->footer_html,
            'default_nomor_format' => $this->default_nomor_format,
            'is_active' => $this->is_active,
            'created_by' => auth()->id(),
        ];

        if ($this->templateId) {
            SuratTemplate::findOrFail($this->templateId)->update($data);
            session()->flash('message', 'Template berhasil diupdate.');
        } else {
            SuratTemplate::create($data);
            session()->flash('message', 'Template berhasil dibuat.');
        }

        return redirect()->route('surat.template.index');
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
        return view('livewire.surat.template.form')->with([
            'varGroups' => $this->variableGroups,
        ]);
    }
}
