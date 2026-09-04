# Agent.md — Panduan untuk AI Coding Agent (SIDESA)

Dokumen ini untuk AI agent (Claude Code atau sejenis) yang membantu membangun proyek ini bersama developer manusia. Baca `prd.md` dan `design.md` dulu sebelum mulai coding.

## 1. Konteks Proyek

Aplikasi web Laravel + Livewire untuk desa: manajemen data penduduk, rumah, peta desa, layanan surat online. Lihat `prd.md` untuk requirement lengkap dan `design.md` untuk arsitektur & data model.

## 2. Stack Wajib

- Laravel 11, Livewire 3, Tailwind CSS, Alpine.js
- Jangan tambahkan framework JS besar (React/Vue) — cukup Livewire + Alpine.
- Jangan ganti database engine tanpa konfirmasi ke developer.

## 3. Coding Conventions

- Ikuti PSR-12 untuk PHP.
- Nama class, method, variabel, kolom database: **Bahasa Inggris** (`Penduduk` boleh tetap istilah domain, tapi properti umum tetap English style: `createdAt`, bukan campur aduk).
  - **Kecuali istilah domain spesifik Indonesia** (NIK, KK, RT/RW, dusun, RTLH) — biarkan apa adanya, jangan dipaksa terjemahkan ke Inggris karena istilah ini baku di konteks pemerintahan desa.
- Label UI, pesan validasi, dan teks yang dilihat user: **Bahasa Indonesia**.
- Gunakan Livewire full-page component per modul (lihat struktur folder di `design.md`), bukan controller + Blade view terpisah, kecuali untuk endpoint publik sederhana (misal generate PDF).
- Validasi input selalu di server-side (Livewire `rules()` / Form Request), jangan andalkan validasi client-side saja.
- Gunakan Laravel Policy untuk cek akses berbasis role — jangan taruh logic role check langsung di Blade view.

## 4. Aturan Bisnis Penting (jangan dilanggar)

- **NIK** harus unik, 16 digit, wajib divalidasi format sebelum simpan.
- **No. KK** harus unik, 16 digit.
- Satu `Penduduk` hanya boleh aktif di satu `KartuKeluarga` pada satu waktu.
- **Penduduk tanpa KK** boleh diinput tanpa `kartu_keluarga_id` (field nullable). Tapi tetap harus ada NIK unik 16 digit. Jangan paksa user memilih KK jika memang belum punya.
- Data NIK dan data pribadi penduduk **tidak boleh** ditampilkan di halaman/route publik (tanpa login). Halaman publik (pengumuman, form ajukan surat) hanya boleh menampilkan/menerima data yang relevan, bukan expose data penduduk lain.
- `kategori_rtlh` pada Rumah sebaiknya dihitung otomatis dari kondisi fisik (lantai/dinding/atap/air/listrik) via helper/service, tapi tetap bisa di-override manual oleh admin — jangan hardcode logic ini di view.

## 5. Style & UI

Ikuti style guide di `design.md` section 2 (warna, tipografi, komponen). Ringkasnya:
- Estetika minimal ala iOS: card rounded-2xl, whitespace luas, satu warna aksen (`#007AFF`), tanpa gradient/shadow berlebihan.
- Gunakan Tailwind utility classes langsung; kalau ada komponen yang dipakai berulang (card, badge, button), buat Blade component reusable di `resources/views/components/`.
- Selalu cek tampilan mobile-responsive — banyak user akan akses dari HP.

## 6. Testing

- Gunakan Pest (bawaan Laravel 11) untuk testing.
- Setiap Livewire component baru (khususnya form create/edit) minimal punya test: render berhasil, validasi jalan, data tersimpan benar.
- Test khusus untuk aturan bisnis di atas (unique NIK/KK, akses role) wajib ada.

## 7. Git & Commit

- Commit kecil dan fokus per fitur/modul (misal: "Add Penduduk CRUD Livewire component", bukan satu commit besar untuk semua modul).
- Jangan commit file `.env`, `vendor/`, `node_modules/`.
- Migration file harus deskriptif (`create_penduduks_table`, bukan `update_table_1`).

## 8. Cara Kerja dengan Developer (manusia)

- Kalau ada keputusan yang belum jelas di `prd.md` section "Open Questions" atau `design.md` section "Yang Belum Diputuskan" — **tanyakan dulu ke developer**, jangan asumsi sendiri dan lanjut coding.
- Kalau agent menemukan requirement yang ambigu atau berpotensi konflik antar modul, laporkan sebelum implementasi, bukan setelah.
- Update `prd.md`/`design.md` kalau ada keputusan baru yang diambil selama development, supaya dokumen tetap jadi source of truth.

## 9. Setup Lokal (untuk agent yang menjalankan environment)

```bash
composer install
cp .env.example .env
php artisan key:generate
# set DB_CONNECTION=sqlite untuk dev lokal cepat, atau mysql sesuai design.md
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

## 10. Prioritas Implementasi (urutan disarankan)

1. Setup auth (Breeze Livewire) + role dasar
2. Modul Penduduk & Kartu Keluarga (fondasi data)
3. Modul Rumah (bergantung pada KK)
4. Peta Desa (bergantung pada data Rumah + koordinat)
5. Modul Surat (pengajuan + tracking)
6. Pengumuman
7. Dashboard statistik (paling akhir, karena butuh data dari modul lain sudah ada)
