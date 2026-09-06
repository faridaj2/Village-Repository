# Spec: Fitur Surat Lengkap (SIDESA)

## Overview

Sistem informasi surat desa yang memungkinkan admin membuat surat resmi desa dari template, dengan auto-fill data penduduk, generate PDF, dan arsip digital.

---

## 1. Database Schema

### 1.1 Tabel `surat_templates` (BARU)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | PK |
| `nama` | string | Nama template, misal "Surat Keterangan Domisili" |
| `slug` | string | Slug unik, misal `domisili`, `tidak_mampu` |
| `jenis_surat` | string | Jenis surat: `domisili`, `tidak_mampu`, `usaha`, `pengantar_ktp_kk`, `kelahiran`, `kematian`, `pindah`, `belum_menikah`, `custom` |
| `header_html` | text | HTML kop surat (atau referensi gambar kop) |
| `body_html` | longText | Template body surat dengan placeholder |
| `footer_html` | text | Footer surat (tanda tangan, tanggal, dll) |
| `default_nomor_format` | string | Format nomor default, misal `{nomor}/SRT/{jenis}/{bulan_romawi}/{tahun}` |
| `is_active` | boolean | Apakah template aktif |
| `created_by` | FK → users | Admin yang buat |
| `timestamps` | | |

**Placeholder syntax:**
```
{{penduduk.nama}}        → Nama penduduk
{{penduduk.nik}}         → NIK
{{penduduk.tempat_lahir}} → Tempat lahir
{{penduduk.tanggal_lahir}} → Tanggal lahir (dd/mm/yyyy)
{{penduduk.jenis_kelamin}} → Laki-laki / Perempuan
{{penduduk.agama}}        → Agama
{{penduduk.pekerjaan}}    → Pekerjaan
{{penduduk.pendidikan_terakhir}} → Pendidikan

{{kk.no_kk}}             → Nomor KK
{{kk.kepala_keluarga.nama}} → Nama KK
{{kk.alamat}}            → Alamat KK

{{penduduk.rumah.kode_rumah}} → Kode rumah
{{penduduk.rumah.alamat}} → Alamat rumah
{{penduduk.rumah.rt.nama}} → RT
{{penduduk.rumah.rt.rw.nama}} → RW

{{desa.nama_desa}}       → Nama desa
{{desa.nama_kecamatan}}  → Kecamatan (dari admin_settings)
{{desa.nama_kabupaten}}  → Kabupaten
{{desa.nama_provinsi}}   → Provinsi

{{surat.nomor}}          → Nomor surat
{{surat.tanggal}}        → Tanggal surat
{{surat.bulan}}          → Nama bulan (Januari, dst)
{{surat.bulan_romawi}}   → Bulan romawi (I, II, III, dst)
{{surat.tahun}}          → Tahun
{{surat.jenis}}          → Jenis surat

{{ttd.nama}}             → Nama penandatangan
{{ttd.jabatan}}          → Jabatan penandatangan
```

### 1.2 Tabel `surats` (UPDATE)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | PK |
| `nomor_surat` | string, unique | Nomor surat resmi, misal `001/SRT/DOM/IX/2026` |
| `nomor_tracking` | string, unique | Untuk tracking warga (tetap ada) |
| `surat_template_id` | FK → surat_templates | Template yang digunakan |
| `penduduk_id` | FK → penduduks | Pemohon/subjek surat |
| `jenis_surat` | string | Jenis surat (di-inherit dari template) |
| `data_isian` | json | Data yang diisi admin (override/auto-fill) |
| `nomor_manual` | string, nullable | Jika admin input nomor manual |
| `status` | enum | `draft`, `diproses`, `selesai`, `ditolak`, `dibatalkan` |
| `catatan_admin` | text, nullable | Catatan/revisi |
| `draft_html` | longText, nullable | HTML final surat setelah render |
| `created_by` | FK → users | Admin yang buat |
| `timestamps` | | |

**Status flow:**
```
draft → diproses → selesai
                    ditolak (→ draft untuk revisi)
                    dibatalkan
```

### 1.3 Tabel `surat_print_logs` (BARU)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | PK |
| `surat_id` | FK → surats | Surat yang dicetak |
| `user_id` | FK → users | Siapa yang cetak |
| `printed_at` | timestamp | Kapan dicetak |
| `format` | enum | `pdf`, `print` |

### 1.4 Tabel `admin_settings` (UPDATE)

Tambah field untuk info desa yang diperlukan surat:

```php
// Keys yang ditambahkan:
'nama_kecamatan'  => 'Kecamatan XYZ'
'nama_kabupaten'  => 'Kabupaten ABC'
'nama_provinsi'   => 'Provinsi DEF'
'logo_desa'       => '/storage/uploads/logo.png'  // Logo untuk kop surat
'kop_surat_image'  => '/storage/uploads/kop.png'   // Gambar kop surat
'ttd_kades_nama'   => 'Budi Santoso'
'ttd_kades_jabatan'=> 'Kepala Desa Waeleman'
'ttd_sekdes_nama'  => 'Andi Cahyadi'
'ttd_sekdes_jabatan'=> 'Sekretaris Desa'
```

---

## 2. Livewire Components

### 2.1 `Surat\Template\Index` — Daftar Template

- Tabel semua template surat
- Kolom: Nama, Jenis, Status (aktif/inaktif), Dibuat Oleh, Aksi
- Aksi: Edit, Clone, Aktifkan/Nonaktifkan, Hapus
- Tombol "Buat Template Baru"

### 2.2 `Surat\Template\Form` — Buat/Edit Template

- Field: Nama Template, Slug (auto-generate), Jenis Surat, Status Aktif
- **Body editor**: HTML editor (Quill/Triple.js) dengan toolbar
- **Insert variable button**: Dropdown untuk sisipkan placeholder
  - Group: Data Penduduk, Data KK, Data Rumah, Data Desa, Info Surat, Tanda Tangan
  - Klik → insert `{{variable}}` ke cursor
- **Preview**: Tombol preview dengan sample data
- **Header/Footer editor** (opsional): Override default kop

### 2.3 `Surat\Create` — Buat Surat Baru (Admin)

**Step 1: Pilih Template**
- Grid/card list template aktif
- Filter berdasarkan jenis surat

**Step 2: Pilih Penduduk**
- Search by NIK atau nama
- Auto-fill semua data dari database

**Step 3: Isi/Review**
- Form fields berdasarkan template:
  - Nomor surat (auto-generate atau manual input)
  - Tanggal surat
  - Field-field tambahan yang diperlukan template
- **Live preview** panel di sebelah kanan

**Step 4: Finalisasi**
- Tombol "Simpan Draft" — simpan sebagai draft
- Tombol "Final & Generate" — render HTML final, lock
- Tombol "Download PDF" — generate PDF

### 2.4 `Surat\Detail` — Detail Surat

- Info surat: Nomor, Status, Tanggal, Pemohon
- **Preview surat** (HTML render)
- Aksi:
  - Download PDF (jika status `selesai`)
  - Cetak (button print via browser)
  - Revisi (kembali ke draft, edit)
  - Batalkan (status → dibatalkan)
- Riwayat cetak (log kapan dan berapa kali dicetak)

### 2.5 `Surat\Index` (UPDATE) — Daftar Surat Admin

- Tabel surat dengan filter:
  - Status: Semua, Draft, Diproses, Selesai, Ditolak, Dibatalkan
  - Jenis surat
  - Tanggal (range)
- Pencarian: Nomor surat, Nama pemohon, NIK
- Kolom: No. Surat, Pemohon, Jenis, Status, Tanggal, Aksi
- Pagination

### 2.6 `Surat\Archive` — Arsip Surat

- Grid/card view surat selesai per tahun
- Filter: Tahun, Jenis Surat
- Bulk download (pilih beberapa → download ZIP)

### 2.7 `Settings\DesaForm` (UPDATE) — Info Surat

Tambah section di halaman settings desa:
- Upload Logo Desa (untuk kop surat)
- Upload Gambar Kop Surat
- Info Penandatangan:
  - Nama & Jabatan Kepala Desa
  - Nama & Jabatan Sekretaris Desa

---

## 3. Views

### 3.1 Template List (`surat/template/index.blade.php`)
- Card grid layout
- Setiap card: nama, jenis, status, preview thumbnail
- Aksi: Edit, Clone, Toggle active

### 3.2 Template Editor (`surat/template/form.blade.php`)
- Split layout: editor kiri, preview kanan
- Variable picker sidebar
- Toolbar: Bold, Italic, Heading, Table, List, Image

### 3.3 Create Surat (`surat/create.blade.php`)
- Wizard/stepper layout: 3 step
- Step indicator di atas
- Back/Next/Submit buttons

### 3.4 Detail Surat (`surat/detail.blade.php`)
- Info card + preview surat
- Aksi bar (Download, Print, Revisi, Batalkan)
- Riwayat cetak section

---

## 4. PDF Generation

### Library
- **dompdf** (sudah umum di Laravel ecosystem)
- Install: `composer require barryvdh/laravel-dompdf`

### Implementation
```php
// SuratService::generatePdf($surat)
// 1. Render HTML dari draft_html
// 2. Set paper size (A4)
// 3. Generate PDF
// 4. Save to storage/app/surat/{year}/{nomor}.pdf
// 5. Return PDF response
```

### Storage structure
```
storage/app/surat/
├── 2026/
│   ├── 001-SRT-DOM-IX-2026.pdf
│   ├── 002-SRT-TM-IX-2026.pdf
│   └── ...
├── 2027/
│   └── ...
```

---

## 5. Numbering System

### Format default
```
{nomor_urut}/SRT/{kode_jenis}/{bulan_romawi}/{tahun}
```

**Contoh:**
- `001/SRT/DOM/IX/2026` — Surat Domisili pertama September 2026
- `002/SRT/TM/IX/2026` — Surat Tidak Mampu kedua September 2026

### Kode jenis surat
| Jenis | Kode |
|-------|------|
| Domisili | DOM |
| Tidak Mampu | TM |
| Pengantar KTP/KK | PENG |
| Usaha | USA |
| Kelahiran | KEL |
| Kematian | KMT |
| Pindah | PND |
| Belum Menikah | BMN |
| Custom | (di-set di template) |

### Auto-generate logic
```php
$jenis = $template->slug; // atau custom code
$bulan = now()->format('m');
$tahun = now()->format('Y');
$bulanRomawi = convertToRoman($bulan);

$lastNumber = Surat::where('jenis_surat', $jenis)
    ->whereYear('created_at', $tahun)
    ->whereMonth('created_at', $bulan)
    ->count();

$nomor = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
$nomorSurat = "{$nomor}/SRT/{$jenis}/{$bulanRomawi}/{$tahun}";
```

### Manual override
- Admin bisa input nomor manual di form create surat
- Jika manual, skip auto-generate

---

## 6. Workflow

### 6.1 Admin Create Surat
```
1. Pilih template → 2. Pilih penduduk → 3. Review & isi → 4. Generate
```

### 6.2 Status Transitions
```
draft → diproses → selesai
  ↑                   ├── ditolak → draft (revisi)
  │                   └── dibatalkan
  └── diproses → dibatalkan
```

### 6.3 Revisi
- Admin klik "Revisi" pada surat selesai
- Status → draft
- Admin edit data/isian
- Generate ulang draft_html
- Finalisasi lagi

### 6.4 Pembatalan
- Admin klik "Batalkan"
- Konfirmasi dialog
- Status → dibatalkan
- Tidak bisa diubah lagi

---

## 7. Routes

```php
// Admin routes
Route::get('surat', SuratIndex::class)->name('surat.index');
Route::get('surat/kelola', SuratKelola::class)->name('surat.kelola');
Route::get('surat/create', SuratCreate::class)->name('surat.create');
Route::get('surat/{surat}', SuratDetail::class)->name('surat.detail');
Route::get('surat/{surat}/pdf', SuratPdf::class)->name('surat.pdf');
Route::get('surat/archive', SuratArchive::class)->name('surat.archive');

// Template routes
Route::get('surat/templates', TemplateIndex::class)->name('surat.template.index');
Route::get('surat/templates/create', TemplateForm::class)->name('surat.template.create');
Route::get('surat/templates/{template}/edit', TemplateForm::class)->name('surat.template.edit');

// Public routes (tetap ada)
Route::get('surat/ajukan', Ajukan::class)->name('surat.ajukan');
Route::get('surat/tracking', Tracking::class)->name('surat.tracking');
```

---

## 8. Dependencies

```bash
composer require barryvdh/laravel-dompdf
```

---

## 9. Storage Directory

```bash
mkdir -p storage/app/surat
mkdir -p storage/app/templates
mkdir -p storage/app/kop
```

---

## 10. Migration Strategy

### Migration 1: Create `surat_templates` table
```php
Schema::create('surat_templates', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('slug')->unique();
    $table->string('jenis_surat');
    $table->longText('header_html')->nullable();
    $table->longText('body_html');
    $table->longText('footer_html')->nullable();
    $table->string('default_nomor_format')->nullable();
    $table->boolean('is_active')->default(true);
    $table->foreignId('created_by')->nullable()->constrained('users');
    $table->timestamps();
});
```

### Migration 2: Update `surats` table
```php
Schema::table('surats', function (Blueprint $table) {
    $table->string('nomor_surat')->nullable()->after('id');
    $table->foreignId('surat_template_id')->nullable()->after('nomor_surat')->constrained('surat_templates');
    $table->json('data_isian')->nullable()->after('data_tambahan');
    $table->string('nomor_manual')->nullable()->after('data_isian');
    $table->enum('status', ['draft','diproses','selesai','ditolak','dibatalkan'])->default('draft')->change();
    $table->longText('draft_html')->nullable()->after('catatan_admin');
    $table->foreignId('created_by')->nullable()->after('draft_html')->constrained('users');
});
```

### Migration 3: Create `surat_print_logs` table
```php
Schema::create('surat_print_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
    $table->foreignId('user_id')->constrained('users');
    $table->timestamp('printed_at');
    $table->enum('format', ['pdf', 'print']);
    $table->timestamps();
});
```

---

## 11. Placeholder Variables Reference

### Data Penduduk
| Placeholder | Deskripsi | Contoh |
|-------------|-----------|--------|
| `{{penduduk.nama}}` | Nama lengkap | Ahmad 1 |
| `{{penduduk.nik}}` | NIK 16 digit | 3200000000000001 |
| `{{penduduk.tempat_lahir}}` | Tempat lahir | Bandung |
| `{{penduduk.tanggal_lahir}}` | Tgl lahir (dd/mm/yyyy) | 26/10/2004 |
| `{{penduduk.tanggal_lahir_panjang}}` | Tgl lahir panjang | 26 Oktober 2004 |
| `{{penduduk.jenis_kelamin}}` | L/P → Laki-laki/Perempuan | Laki-laki |
| `{{penduduk.agama}}` | Agama | Islam |
| `{{penduduk.pekerjaan}}` | Pekerjaan | Wiraswasta |
| `{{penduduk.pendidikan}}` | Pendidikan terakhir | SMA |
| `{{penduduk.status_kawin}}` | Status kawin | Kawin |
| `{{penduduk.umur}}` | Umur (auto hitung) | 21 tahun |

### Data KK
| Placeholder | Deskripsi |
|-------------|-----------|
| `{{kk.no_kk}}` | Nomor KK |
| `{{kk.kepala_keluarga}}` | Nama kepala keluarga |
| `{{kk.alamat}}` | Alamat dari KK |

### Data Rumah & Lokasi
| Placeholder | Deskripsi |
|-------------|-----------|
| `{{rumah.kode}}` | Kode rumah |
| `{{rumah.alamat}}` | Alamat rumah |
| `{{rt}}` | RT (misal: RT 001) |
| `{{rw}}` | RW (misal: RW 001) |

### Data Desa
| Placeholder | Deskripsi |
|-------------|-----------|
| `{{desa.nama}}` | Nama desa |
| `{{desa.kecamatan}}` | Nama kecamatan |
| `{{desa.kabupaten}}` | Nama kabupaten |
| `{{desa.provinsi}}` | Nama provinsi |

### Info Surat
| Placeholder | Deskripsi |
|-------------|-----------|
| `{{surat.nomor}}` | Nomor surat |
| `{{surat.tanggal}}` | Tanggal surat (dd) |
| `{{surat.bulan}}` | Nama bulan (Indonesia) |
| `{{surat.bulan_romawi}}` | Bulan romawi |
| `{{surat.tahun}}` | Tahun |
| `{{surat.jenis}}` | Jenis surat |

### Tanda Tangan
| Placeholder | Deskripsi |
|-------------|-----------|
| `{{ttd.nama}}` | Nama penandatangan |
| `{{ttd.jabatan}}` | Jabatan penandatangan |

---

## 12. UI Design Notes

- **Template Editor**: Split layout 60/40 — editor kiri, live preview kanan
- **Create Surat**: Wizard stepper 3 langkah dengan progress indicator
- **Detail Surat**: Card info + full preview surat + aksi buttons
- **Arsip**: Grid/timeline view per tahun, card per surat
- Follow existing design system: rounded-2xl, gradient buttons, card shadows

---

## 13. File Structure (New/Modified)

```
app/
├── Livewire/
│   └── Surat/
│       ├── Template/
│       │   ├── Index.php          (BARU)
│       │   └── Form.php           (BARU)
│       ├── Create.php             (BARU)
│       ├── Detail.php             (BARU)
│       ├── Archive.php            (BARU)
│       ├── Index.php              (UPDATE - tambah filter)
│       └── Kelola.php             (UPDATE - alur baru)
├── Models/
│   ├── SuratTemplate.php          (BARU)
│   └── SuratPrintLog.php          (BARU)
├── Services/
│   └── SuratService.php           (BARU - render, PDF, numbering)
resources/views/livewire/surat/
├── template/
│   ├── index.blade.php            (BARU)
│   └── form.blade.php             (BARU)
├── create.blade.php               (BARU)
├── detail.blade.php               (BARU)
├── archive.blade.php              (BARU)
├── index.blade.php                (UPDATE)
└── kelola.blade.php               (UPDATE)
database/migrations/
└── XXX_create_surat_system_tables.php (BARU)
```

---

## 14. Open Questions

- [ ] Logo desa & kop surat: upload di mana? Settings desa atau di template?
- [ ] Apakah perlu role-based access (misal: hanya sekdes yang bisa finalisasi)?
- [ ] Apakah warga bisa melihat preview surat mereka via tracking?
- [ ] Format bulan romawi: I, II, III... atau i, ii, iii...?
- [ ] Apakah perlu email notifikasi saat surat selesai?
