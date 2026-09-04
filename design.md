# Design.md — SIDESA

## 1. Tech Stack

| Layer | Pilihan | Alasan |
|---|---|---|
| Backend framework | Laravel 11 | Sesuai preferensi, ekosistem matang |
| Frontend interaktivity | Livewire 3 | Full-stack PHP, tanpa perlu API terpisah/JS framework berat |
| CSS | Tailwind CSS | Cepat untuk styling custom, cocok untuk desain minimal ala iOS |
| JS ringan | Alpine.js (bundled Livewire) | Untuk interaksi kecil (modal, dropdown) tanpa build step berat |
| Database | MySQL (prod) / SQLite (local dev) | MySQL umum di hosting shared/VPS Indonesia |
| Peta | Leaflet.js + OpenStreetMap | Gratis, tidak perlu API key seperti Google Maps |
| PDF generator | barryvdh/laravel-dompdf | Untuk cetak surat & laporan |
| Auth | Laravel Breeze (Livewire stack) | Sudah include auth scaffolding + Livewire |
| Icon | Heroicons (outline) | Style garis tipis, senada dengan estetika iOS |
| Font | Inter (fallback ke -apple-system, SF Pro) | Font terdekat dengan SF Pro yang free & web-safe |

## 2. Style Guide — "Simpel ala iPhone"

Prinsip desain: **bersih, banyak whitespace, satu warna aksen, kartu (card) sebagai unit utama, tanpa border tebal/gradasi ramai.**

### Warna
```css
--color-bg: #F2F2F7;        /* iOS systemGray6 - background utama */
--color-surface: #FFFFFF;    /* card/panel */
--color-primary: #007AFF;    /* iOS systemBlue - warna aksen utama */
--color-success: #34C759;    /* iOS systemGreen */
--color-warning: #FF9500;    /* iOS systemOrange */
--color-danger: #FF3B30;     /* iOS systemRed */
--color-text-primary: #1C1C1E;
--color-text-secondary: #8E8E93;
--color-border: #E5E5EA;
```

### Tipografi
- Font: `Inter, -apple-system, BlinkMacSystemFont, "SF Pro Display", sans-serif`
- Judul halaman: 28px, semibold
- Judul section/card: 17px, semibold
- Body text: 15px, regular
- Caption/label kecil: 13px, text-secondary

### Komponen
- **Card**: `rounded-2xl`, `shadow-sm`, background putih, padding `p-4`/`p-6`, tanpa border tebal (kalau perlu border pakai `border border-gray-100`)
- **Button primary**: rounded penuh (`rounded-full`) atau `rounded-xl`, warna `--color-primary`, tanpa shadow berlebihan
- **List item**: gaya iOS grouped list — separator tipis antar item, chevron `>` di kanan untuk item yang bisa diklik
- **Navigasi**: sidebar minimal untuk admin (desktop), bottom tab bar untuk mobile-web
- **Form input**: rounded-xl, background abu muda (`--color-bg`), tanpa border sampai fokus (baru muncul ring biru saat fokus)
- **Modal**: muncul dari bawah di mobile (seperti iOS sheet), center modal di desktop
- **Badge/status**: pill kecil dengan warna sesuai status (hijau=selesai, oranye=diproses, merah=ditolak)

### Prinsip Layout
- Whitespace generous, jangan padat.
- Satu aksi utama per layar, aksi sekunder ditempatkan lebih halus (link/button outline).
- Hindari warna terlalu banyak — 1 warna aksen (biru) + grayscale + warna status saja.
- Animasi transisi halus (Livewire's `wire:transition` / Alpine `x-transition`), hindari perubahan tiba-tiba.

## 3. Arsitektur Aplikasi

```
Browser (Livewire components, Alpine.js, Tailwind)
        |
Laravel App (routes/web.php → Livewire Full-Page Components)
        |
   ┌────┴────┬─────────────┬──────────────┐
Models     Policies      Services      Livewire Actions
(Eloquent) (Role-based   (PDF gen,     (form handling,
            access)       geo helpers)   validation)
        |
    MySQL Database
```

Pendekatan: **Livewire full-page components per modul** (bukan controller + blade view terpisah), supaya interaktivitas (search, filter, pagination) tidak perlu reload halaman.

## 4. Struktur Folder (kunci)

```
app/
  Livewire/
    Penduduk/
      Index.php        # list + search/filter
      Form.php         # create/edit (modal atau halaman)
      Detail.php
      AssignKK.php     # assign penduduk tanpa KK ke KK yang ada
    Rumah/
      Index.php
      Form.php
      PetaDesa.php      # komponen peta interaktif
    Surat/
      Ajukan.php        # form publik warga
      Tracking.php       # cek status publik
      Kelola.php         # admin proses surat
    Pengumuman/
      Index.php
    Dashboard/
      Statistik.php
  Models/
    Penduduk.php
    KartuKeluarga.php
    Rumah.php
    Surat.php
    Pengumuman.php
    User.php
  Policies/
    PendudukPolicy.php
    RumahPolicy.php
    SuratPolicy.php
  Services/
    SuratPdfService.php
    StatistikService.php

resources/views/
  livewire/            # view untuk tiap komponen di atas
  layouts/
    app.blade.php       # layout admin (sidebar)
    guest.blade.php      # layout publik (warga)
  components/            # reusable blade components (card, badge, dll)
```

## 5. Data Model (ERD ringkas)

```
KartuKeluarga (1) ───< (N) Penduduk
KartuKeluarga (N) >─── (1) Rumah          [satu rumah bisa banyak KK]
Penduduk       (1) ───< (N) MutasiPenduduk
Rumah          (1) ───< (N) FotoRumah      [opsional, kalau multi-foto]
Dusun          (1) ───< (N) Rumah
Dusun          (1) ───< (N) FasilitasUmum
Surat          (N) >─── (1) Penduduk (pemohon)
User           (N) >─── (1) Role           [role: super_admin, operator, kadus]
Pengumuman     — standalone
```

### Tabel utama & kolom penting

**penduduks**
`nik (unique, 16 char)`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_kawin`, `kartu_keluarga_id` (**nullable** — untuk warga yang sudah menetap tapi belum punya KK), `status_khusus` (json: disabilitas/lansia/miskin), `status` (aktif/pindah/meninggal)

**kartu_keluargas**
`no_kk (unique, 16 char)`, `kepala_keluarga_id` (FK ke penduduk), `rumah_id` (FK, nullable), `alamat`

**rumahs**
`kode_rumah`, `dusun_id`, `alamat`, `latitude`, `longitude`, `foto`, `status_kepemilikan`, `jenis_lantai`, `jenis_dinding`, `jenis_atap`, `sumber_air`, `punya_mck` (bool), `sumber_listrik`, `kategori_rtlh` (enum: layak/tidak_layak), `luas_bangunan`, `luas_tanah`

**dusuns**
`nama`, `boundary_geojson` (text, untuk polygon batas wilayah)

**surats**
`nomor_tracking (unique)`, `penduduk_id`, `jenis_surat`, `data_tambahan` (json), `status` (diajukan/diproses/selesai/ditolak), `catatan_admin`, `file_pdf`

**pengumumans**
`judul`, `isi`, `kategori`, `tanggal_publish`, `is_published`

## 6. Peta Desa — Implementasi

- Base layer: OpenStreetMap tiles via Leaflet.
- Marker rumah: warna beda per kategori RTLH (hijau=layak, merah=tidak layak).
- Marker fasilitas umum: icon custom per jenis (masjid, sekolah, dll).
- Polygon batas dusun: digambar via Leaflet Draw plugin saat setup awal (oleh admin), disimpan sebagai GeoJSON di kolom `boundary_geojson`.
- Klik marker → popup Livewire (atau simple info window) menampilkan ringkasan data, link ke detail.
- Filter panel: dropdown dusun + toggle kategori RTLH, meng-update marker via Livewire tanpa reload.

## 7. Auth & Role

- Laravel Breeze (Livewire) untuk login perangkat desa.
- Role disimpan di kolom `role` pada tabel `users` (enum sederhana, tidak perlu package permission kompleks di v1).
- Middleware/Policy cek role di tiap Livewire component (`mount()` method check).
- Halaman warga (ajukan surat, tracking, pengumuman) **tanpa login** — akses via route publik terpisah.

## 8. Penduduk Tanpa KK — Implementasi

**Fitur:** Mendata warga yang sudah menetap tapi belum memiliki Kartu Keluarga.

**Perubahan data model:**
- Kolom `kartu_keluarga_id` pada tabel `penduduks` dijadikan **nullable**
- Field KK tidak wajib diisi saat input/edit penduduk

**UI & UX:**
- Badge oranye "Tanpa KK" di daftar penduduk untuk yang belum punya KK
- Filter khusus di halaman Index Penduduk: "Tampilkan yang Tanpa KK"
- Di halaman detail penduduk, tampilkan tombol "Assign ke KK" jika belum punya KK
- Ketika assign, pilih KK dari dropdown (hanya KK yang ada di sistem)
- Riwayat perubahan status (null → terdaftar di KK) tercatat untuk audit

**Aturan bisnis:**
- Penduduk tanpa KK tetap valid untuk diinput (tidak wajib KK)
- Satu penduduk hanya bisa terdaftar di satu KK aktif
- Assign KK hanya bisa dilakukan oleh admin (Operator ke atas)

## 9. Yang Belum Diputuskan (perlu riset/keputusan lanjut)

- Verifikasi identitas warga saat ajukan surat online (OTP HP? Cukup NIK+No.KK?)
- Hosting target (shared hosting / VPS) — mempengaruhi pilihan MySQL vs SQLite di prod.
- Kebutuhan multi-desa dalam satu instalasi (multi-tenant) atau 1 instalasi = 1 desa saja? *(asumsi saat ini: 1 desa per instalasi)*
