<?php

use App\Livewire\Penduduk\Index as PendudukIndex;
use App\Livewire\Penduduk\Form as PendudukForm;
use App\Livewire\Penduduk\Detail as PendudukDetail;
use App\Livewire\KartuKeluarga\Index as KartuKeluargaIndex;
use App\Livewire\KartuKeluarga\Form as KartuKeluargaForm;
use App\Livewire\KartuKeluarga\Detail as KartuKeluargaDetail;
use App\Livewire\Peta\Index as PetaIndex;
use App\Livewire\Surat\Index as SuratIndex;
use App\Livewire\Surat\Kelola as SuratKelola;
use App\Livewire\Surat\Create as SuratCreate;
use App\Livewire\Surat\Detail as SuratDetail;
use App\Livewire\Surat\Archive as SuratArchive;
use App\Livewire\Surat\Template\Index as TemplateIndex;
use App\Livewire\Surat\Template\Form as TemplateForm;
use App\Livewire\Surat\Manual\Index as SuratManualIndex;
use App\Livewire\Surat\Manual\Form as SuratManualForm;
use App\Livewire\Surat\Manual\Blok as SuratManualBlok;
use App\Livewire\Pengumuman\Index as PengumumanIndex;
use App\Livewire\FasilitasUmum\Index as FasilitasUmumIndex;
use App\Livewire\Wilayah\Index as WilayahIndex;
use App\Livewire\Rumah\Index as RumahIndex;
use App\Livewire\Rumah\Form as RumahForm;
use App\Livewire\StrukturPemerintahan\Index as StrukturPemerintahanIndex;
use App\Livewire\AdminSettings\Index as AdminSettingsIndex;
use App\Livewire\User\Index as UserIndex;
use App\Livewire\Dashboard\Statistik;
use App\Livewire\Media\Index as MediaIndex;
use App\Services\SuratService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::view('/', 'welcome');

// Public routes
Route::get('pengumuman/publik', \App\Livewire\Pengumuman\Publik::class)->name('pengumuman.publik');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', Statistik::class)->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Penduduk
    Route::get('penduduk', PendudukIndex::class)->name('penduduk.index');
    Route::get('penduduk/create', PendudukForm::class)->name('penduduk.create');
    Route::get('penduduk/{penduduk}/edit', PendudukForm::class)->name('penduduk.edit');
    Route::get('penduduk/{penduduk}', PendudukDetail::class)->name('penduduk.show');

    // Kartu Keluarga
    Route::get('kartu-keluarga', KartuKeluargaIndex::class)->name('kartu-keluarga.index');
    Route::get('kartu-keluarga/create', KartuKeluargaForm::class)->name('kartu-keluarga.create');
    Route::get('kartu-keluarga/{kartuKeluarga}/edit', KartuKeluargaForm::class)->name('kartu-keluarga.edit');
    Route::get('kartu-keluarga/{kartuKeluarga}', KartuKeluargaDetail::class)->name('kartu-keluarga.show');

    // Peta
    Route::get('peta', PetaIndex::class)->name('peta.index');

    // Surat
    Route::get('surat', SuratIndex::class)->name('surat.index');
    Route::get('surat/kelola', SuratKelola::class)->name('surat.kelola');
    Route::get('surat/create', SuratCreate::class)->name('surat.create');
    Route::get('surat/archive', SuratArchive::class)->name('surat.archive');
    Route::get('surat/templates', TemplateIndex::class)->name('surat.template.index');
    Route::get('surat/templates/create', TemplateForm::class)->name('surat.template.create');
    Route::get('surat/templates/{id}/edit', TemplateForm::class)->name('surat.template.edit');

    // Surat Manual (tanpa template)
    Route::get('surat-manual', SuratManualIndex::class)->name('surat.manual.index');
    Route::get('surat-manual/create', SuratManualForm::class)->name('surat.manual.create');
    Route::get('surat-manual/{id}/edit', SuratManualForm::class)->name('surat.manual.edit');
    Route::get('surat-manual-blok', SuratManualBlok::class)->name('surat.manual.blok');

    Route::get('surat/{surat}', SuratDetail::class)->name('surat.detail');
    Route::get('surat/{surat}/pdf', function (\App\Models\Surat $surat) {
        $path = SuratService::getOrCreatePdf($surat);
        $filename = str_replace('/', '-', $surat->nomor_display) . '.pdf';

        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    })->name('surat.pdf');

    // Media / File
    Route::get('media', MediaIndex::class)->name('media.index');

    // Pengumuman
    Route::get('pengumuman', PengumumanIndex::class)->name('pengumuman.index');

    // Fasilitas Umum
    Route::get('fasilitas-umum', FasilitasUmumIndex::class)->name('fasilitas-umum.index');

    // Wilayah (RW/RT)
    Route::get('wilayah', WilayahIndex::class)->name('wilayah.index');

    // Rumah
    Route::get('rumah', RumahIndex::class)->name('rumah.index');
    Route::get('rumah/create', RumahForm::class)->name('rumah.create');
    Route::get('rumah/{rumah}/edit', RumahForm::class)->name('rumah.edit');

    // Struktur Pemerintahan
    Route::get('struktur-pemerintahan', StrukturPemerintahanIndex::class)->name('struktur-pemerintahan.index');

    // Admin Settings
    Route::get('admin-settings', AdminSettingsIndex::class)->name('admin-settings.index');

    // User Management
    Route::get('user', UserIndex::class)->name('user.index');
});

require __DIR__.'/auth.php';
