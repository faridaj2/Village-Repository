<?php

namespace App\Livewire\AdminSettings;

use App\Models\AdminSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Pengaturan Sistem')]
class Index extends Component
{
    // Hero Sliders
    public $sliders = [];

    // General
    public $namaDesa = '';
    public $sambutan = '';
    public $enableRegistration = true;

    // Identitas Desa & Tanda Tangan (dipakai template surat)
    public $kecamatan = '';
    public $kabupaten = '';
    public $provinsi = '';
    public $alamatDesa = '';
    public $kodePos = '';
    public $telepon = '';
    public $email = '';
    public $ttdKadesNama = '';
    public $ttdKadesJabatan = '';
    public $ttdSekdesNama = '';
    public $ttdSekdesJabatan = '';

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->sliders = AdminSetting::get('hero_sliders', [
            ['img' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1600&h=600&fit=crop', 'title' => 'Selamat Datang di Desa Kami', 'sub' => 'Desa yang asri, maju, dan sejahtera'],
            ['img' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1600&h=600&fit=crop', 'title' => 'Layanan Desa Digital', 'sub' => 'Ajukan surat online tanpa antri'],
            ['img' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1600&h=600&fit=crop', 'title' => 'Gotong Royong Bersama', 'sub' => 'Membangun desa bersama seluruh warga'],
        ]);

        $this->namaDesa = AdminSetting::get('nama_desa', 'Desa Waeleman');
        $this->sambutan = AdminSetting::get('sambutan', 'Selamat datang di Sistem Informasi Desa');
        $this->enableRegistration = AdminSetting::get('enable_registration', true);

        $this->kecamatan = AdminSetting::get('nama_kecamatan', '');
        $this->kabupaten = AdminSetting::get('nama_kabupaten', '');
        $this->provinsi = AdminSetting::get('nama_provinsi', '');
        $this->alamatDesa = AdminSetting::get('alamat_desa', '');
        $this->kodePos = AdminSetting::get('kode_pos', '');
        $this->telepon = AdminSetting::get('telepon', '');
        $this->email = AdminSetting::get('email', '');
        $this->ttdKadesNama = AdminSetting::get('ttd_kades_nama', '');
        $this->ttdKadesJabatan = AdminSetting::get('ttd_kades_jabatan', '');
        $this->ttdSekdesNama = AdminSetting::get('ttd_sekdes_nama', '');
        $this->ttdSekdesJabatan = AdminSetting::get('ttd_sekdes_jabatan', '');
    }

    public function addSlider()
    {
        $this->sliders[] = ['img' => '', 'title' => '', 'sub' => ''];
    }

    public function removeSlider($index)
    {
        array_splice($this->sliders, $index, 1);
    }

    public function saveSliders()
    {
        AdminSetting::set('hero_sliders', $this->sliders);
        session()->flash('message', 'Hero slider berhasil disimpan.');
    }

    public function saveGeneral()
    {
        AdminSetting::set('nama_desa', $this->namaDesa);
        AdminSetting::set('sambutan', $this->sambutan);
        AdminSetting::set('enable_registration', $this->enableRegistration);
        session()->flash('message', 'Pengaturan umum berhasil disimpan.');
    }

    public function saveIdentitasSurat()
    {
        AdminSetting::set('nama_desa', $this->namaDesa);
        AdminSetting::set('nama_kecamatan', $this->kecamatan);
        AdminSetting::set('nama_kabupaten', $this->kabupaten);
        AdminSetting::set('nama_provinsi', $this->provinsi);
        AdminSetting::set('alamat_desa', $this->alamatDesa);
        AdminSetting::set('kode_pos', $this->kodePos);
        AdminSetting::set('telepon', $this->telepon);
        AdminSetting::set('email', $this->email);
        AdminSetting::set('ttd_kades_nama', $this->ttdKadesNama);
        AdminSetting::set('ttd_kades_jabatan', $this->ttdKadesJabatan);
        AdminSetting::set('ttd_sekdes_nama', $this->ttdSekdesNama);
        AdminSetting::set('ttd_sekdes_jabatan', $this->ttdSekdesJabatan);
        session()->flash('message', 'Pengaturan identitas desa & tanda tangan berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin-settings.index');
    }
}
