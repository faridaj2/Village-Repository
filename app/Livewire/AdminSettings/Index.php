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

    public function render()
    {
        return view('livewire.admin-settings.index');
    }
}
