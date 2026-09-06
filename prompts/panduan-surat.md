# Panduan Pembuatan Surat Desa - SIDESA

## Konteks Aplikasi

SIDESA (Sistem Informasi Desa) adalah aplikasi manajemen desa berbasis Laravel + Livewire. Fitur surat memungkinkan admin membuat surat resmi desa dari template dengan auto-fill data penduduk dari database.

## Syntax Placeholder

Gunakan placeholder berikut di template HTML. Placeholder akan otomatis diganti dengan data dari database.

### Data Penduduk
| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{{penduduk.nama}}` | Nama lengkap | Ahmad 1 |
| `{{penduduk.nik}}` | NIK 16 digit | 3200000000000001 |
| `{{penduduk.tempat_lahir}}` | Tempat lahir | Bandung |
| `{{penduduk.tanggal_lahir}}` | Tanggal lahir (dd/mm/yyyy) | 26/10/2004 |
| `{{penduduk.tanggal_lahir_panjang}}` | Tanggal lahir panjang | 26 Oktober 2004 |
| `{{penduduk.jenis_kelamin}}` | Laki-laki / Perempuan | Laki-laki |
| `{{penduduk.jenis_kelamin singkat}}` | L / P | L |
| `{{penduduk.agama}}` | Agama | Islam |
| `{{penduduk.pekerjaan}}` | Pekerjaan | Wiraswasta |
| `{{penduduk.pendidikan}}` | Pendidikan terakhir | SMA |
| `{{penduduk.status_kawin}}` | Status kawin | Kawin |
| `{{penduduk.umur}}` | Umur (auto hitung) | 21 tahun |

### Data Kartu Keluarga
| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{{kk.no_kk}}` | Nomor KK | 3200000000000001 |
| `{{kk.kepala_keluarga}}` | Nama kepala keluarga | Ahmad 1 |
| `{{kk.alamat}}` | Alamat dari KK | Jl. Contoh No. 1 |

### Data Rumah & Lokasi
| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{{rumah.kode}}` | Kode rumah | R001 |
| `{{rumah.alamat}}` | Alamat rumah | Jl. Contoh No. 1 |
| `{{rt}}` | RT | RT 1 |
| `{{rw}}` | RW | RW 1 |

### Data Desa
| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{{desa.nama}}` | Nama desa | Desa Waeleman |
| `{{desa.kecamatan}}` | Nama kecamatan | Kecamatan Waeleman |
| `{{desa.kabupaten}}` | Nama kabupaten | Kabupaten Ende |
| `{{desa.provinsi}}` | Nama provinsi | NTT |

### Info Surat
| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{{surat.nomor}}` | Nomor surat | 001/SRT/DOM/IX/2026 |
| `{{surat.tanggal}}` | Tanggal surat (angka) | 05 |
| `{{surat.bulan}}` | Nama bulan (Indonesia) | September |
| `{{surat.bulan_romawi}}` | Bulan romawi | IX |
| `{{surat.tahun}}` | Tahun | 2026 |
| `{{surat.jenis}}` | Jenis surat | Domisili |

### Tanda Tangan
| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{{ttd.nama}}` | Nama penandatangan | Budi Santoso |
| `{{ttd.jabatan}}` | Jabatan penandatangan | Kepala Desa Waeleman |

---

## Format Surat Resmi Indonesia

### Struktur Umum Surat

```
1. Kop Surat (header gambar/HTML)
2. Nomor Surat
3. Lampiran (jika ada)
4. Perihal
5. Isi Surat:
   a. Tempat & Tanggal
   b. Yang bertanda tangan di bawah ini
   c. Data subjek (nama, NIK, alamat)
   d. Pernyataan/keterangan
   e. Keperluan (jika ada)
   f. Penutup
7. Tanda tangan & nama pejabat
8. Stempel (opsional)
```

### Aturan Penulisan

1. **Nama desa** ditulis lengkap: "Desa Waeleman, Kecamatan Waeleman, Kabupaten Ende, Provinsi NTT"
2. **NIK** ditulis tanpa spasi, 16 digit
3. **Tanggal** ditulis dalam format Indonesia: "5 September 2026" (bukan "05/09/2026")
4. **Nama pejabat** ditulis dengan huruf kapital di awal, dibawah ditulis jabatan
5. **Kop surat** berisi nama desa, alamat, telepon, email (jika ada)
6. **Margin kertas**: A4 (210mm x 297mm), margin 2.5cm setiap sisi

---

## Contoh Template

### 1. Surat Keterangan Domisili

```html
<div style="text-align: center; margin-bottom: 30px;">
    <h2 style="margin: 0; text-transform: uppercase;">Surat Keterangan Domisili</h2>
    <p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p>
</div>

<p>Yang bertanda tangan di bawah ini, Kepala Desa {{desa.nama}}, 
Kecamatan {{desa.kecamatan}}, Kabupaten {{desa.kabupaten}}, Provinsi {{desa.provinsi}}, 
dengan ini menerangkan bahwa:</p>

<table style="width: 100%; margin: 20px 0;">
    <tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr>
    <tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr>
    <tr><td>Tempat / Tgl Lahir</td><td>: {{penduduk.tempat_lahir}}, {{penduduk.tanggal_lahir_panjang}}</td></tr>
    <tr><td>Jenis Kelamin</td><td>: {{penduduk.jenis_kelamin}}</td></tr>
    <tr><td>Agama</td><td>: {{penduduk.agama}}</td></tr>
    <tr><td>Pekerjaan</td><td>: {{penduduk.pekerjaan}}</td></tr>
    <tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa {{desa.nama}}</td></tr>
</table>

<p>Bahwa yang bersangkutan berdomisili di wilayah Desa {{desa.nama}} 
dan merupakan penduduk yang sah.</p>

<div style="margin-top: 60px; text-align: right;">
    <p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p>
    <p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">
        {{ttd.nama}}
    </p>
    <p>{{ttd.jabatan}}</p>
</div>
```

### 2. Surat Keterangan Tidak Mampu

```html
<div style="text-align: center; margin-bottom: 30px;">
    <h2 style="margin: 0; text-transform: uppercase;">Surat Keterangan Tidak Mampu</h2>
    <p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p>
</div>

<p>Yang bertanda tangan di bawah ini, Kepala Desa {{desa.nama}}, 
dengan ini menerangkan bahwa:</p>

<table style="width: 100%; margin: 20px 0;">
    <tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr>
    <tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr>
    <tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa {{desa.nama}}</td></tr>
</table>

<p>Bahwa yang bersangkutan tergolong keluarga tidak mampu 
dan membutuhkan bantuan sosial dari Pemerintah.</p>

<p>Surat keterangan ini dibuat untuk keperluan pengajuan bantuan sosial.</p>

<div style="margin-top: 60px; text-align: right;">
    <p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p>
    <p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">
        {{ttd.nama}}
    </p>
    <p>{{ttd.jabatan}}</p>
</div>
```

### 3. Surat Pengantar KTP/KK

```html
<div style="text-align: center; margin-bottom: 30px;">
    <h2 style="margin: 0; text-transform: uppercase;">Surat Pengantar</h2>
    <p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p>
</div>

<p>Yang bertanda tangan di bawah ini, Kepala Desa {{desa.nama}}, 
dengan ini menerangkan bahwa:</p>

<table style="width: 100%; margin: 20px 0;">
    <tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr>
    <tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr>
    <tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa {{desa.nama}}</td></tr>
</table>

<p>Adalah penduduk Desa {{desa.nama}} yang telah memenuhi syarat 
untuk mengurus pembuatan KTP/KK di Kantor Kecamatan {{desa.kecamatan}}.</p>

<p>Demikian surat pengantar ini dibuat dengan sebenarnya.</p>

<div style="margin-top: 60px; text-align: right;">
    <p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p>
    <p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">
        {{ttd.nama}}
    </p>
    <p>{{ttd.jabatan}}</p>
</div>
```

### 4. Surat Keterangan Usaha

```html
<div style="text-align: center; margin-bottom: 30px;">
    <h2 style="margin: 0; text-transform: uppercase;">Surat Keterangan Usaha</h2>
    <p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p>
</div>

<p>Yang bertanda tangan di bawah ini, Kepala Desa {{desa.nama}}, 
dengan ini menerangkan bahwa:</p>

<table style="width: 100%; margin: 20px 0;">
    <tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr>
    <tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr>
    <tr><td>Pekerjaan</td><td>: {{penduduk.pekerjaan}}</td></tr>
    <tr><td>Alamat Usaha</td><td>: RT {{rt}} / RW {{rw}}, Desa {{desa.nama}}</td></tr>
</table>

<p>Bahwa yang bersangkutan memiliki usaha di wilayah Desa {{desa.nama}} 
dan usaha tersebut benar-benar milik yang bersangkutan.</p>

<div style="margin-top: 60px; text-align: right;">
    <p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p>
    <p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">
        {{ttd.nama}}
    </p>
    <p>{{ttd.jabatan}}</p>
</div>
```

### 5. Surat Keterangan Kelahiran

```html
<div style="text-align: center; margin-bottom: 30px;">
    <h2 style="margin: 0; text-transform: uppercase;">Surat Keterangan Kelahiran</h2>
    <p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p>
</div>

<p>Yang bertanda tangan di bawah ini, Kepala Desa {{desa.nama}}, 
dengan ini menerangkan bahwa:</p>

<table style="width: 100%; margin: 20px 0;">
    <tr><td style="width: 200px;">Nama Ibu</td><td>: {{penduduk.nama}}</td></tr>
    <tr><td>NIK Ibu</td><td>: {{penduduk.nik}}</td></tr>
    <tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa {{desa.nama}}</td></tr>
</table>

<p>Telah melahirkan seorang anak pada tanggal <strong>[TANGGAL KELAHIRAN]</strong> 
di <strong>[TEMPAT KELAHIRAN]</strong>.</p>

<table style="width: 100%; margin: 20px 0;">
    <tr><td style="width: 200px;">Jenis Kelamin</td><td>: [LAKI-LAKI / PEREMPUAN]</td></tr>
    <tr><td>Nama Anak</td><td>: [NAMA ANAK]</td></tr>
</table>

<div style="margin-top: 60px; text-align: right;">
    <p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p>
    <p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">
        {{ttd.nama}}
    </p>
    <p>{{ttd.jabatan}}</p>
</div>
```

### 6. Surat Keterangan Kematian

```html
<div style="text-align: center; margin-bottom: 30px;">
    <h2 style="margin: 0; text-transform: uppercase;">Surat Keterangan Kematian</h2>
    <p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p>
</div>

<p>Yang bertanda tangan di bawah ini, Kepala Desa {{desa.nama}}, 
dengan ini menerangkan bahwa:</p>

<table style="width: 100%; margin: 20px 0;">
    <tr><td style="width: 200px;">Nama</td><td>: [NAMA ALMARHUM/ALMARHUMAH]</td></tr>
    <tr><td>NIK</td><td>: [NIK]</td></tr>
    <tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa {{desa.nama}}</td></tr>
</table>

<p>Telah meninggal dunia pada tanggal <strong>[TANGGAL MENINGGAL]</strong> 
di <strong>[TEMPAT MENINGGAL]</strong>.</p>

<p>Surat keterangan ini dibuat untuk keperluan pengurusan administrasi kematian.</p>

<div style="margin-top: 60px; text-align: right;">
    <p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p>
    <p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">
        {{ttd.nama}}
    </p>
    <p>{{ttd.jabatan}}</p>
</div>
```

### 7. Surat Pengantar Pindah

```html
<div style="text-align: center; margin-bottom: 30px;">
    <h2 style="margin: 0; text-transform: uppercase;">Surat Pengantar Pindah</h2>
    <p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p>
</div>

<p>Yang bertanda tangan di bawah ini, Kepala Desa {{desa.nama}}, 
dengan ini menerangkan bahwa:</p>

<table style="width: 100%; margin: 20px 0;">
    <tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr>
    <tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr>
    <tr><td>Alamat Asal</td><td>: RT {{rt}} / RW {{rw}}, Desa {{desa.nama}}</td></tr>
</table>

<p>Akan pindah ke: <strong>[ALAMAT TUJUAN PINDAH]</strong></p>

<p>Demikian surat pengantar pindah ini dibuat dengan sebenarnya.</p>

<div style="margin-top: 60px; text-align: right;">
    <p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p>
    <p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">
        {{ttd.nama}}
    </p>
    <p>{{ttd.jabatan}}</p>
</div>
```

### 8. Surat Keterangan Belum Menikah

```html
<div style="text-align: center; margin-bottom: 30px;">
    <h2 style="margin: 0; text-transform: uppercase;">Surat Keterangan Belum Menikah</h2>
    <p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p>
</div>

<p>Yang bertanda tangan di bawah ini, Kepala Desa {{desa.nama}}, 
dengan ini menerangkan bahwa:</p>

<table style="width: 100%; margin: 20px 0;">
    <tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr>
    <tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr>
    <tr><td>Tempat / Tgl Lahir</td><td>: {{penduduk.tempat_lahir}}, {{penduduk.tanggal_lahir_panjang}}</td></tr>
    <tr><td>Jenis Kelamin</td><td>: {{penduduk.jenis_kelamin}}</td></tr>
    <tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa {{desa.nama}}</td></tr>
</table>

<p>Bahwa yang bersangkutan belum pernah menikah dan saat ini masih single/belum menikah.</p>

<div style="margin-top: 60px; text-align: right;">
    <p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p>
    <p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">
        {{ttd.nama}}
    </p>
    <p>{{ttd.jabatan}}</p>
</div>
```

---

## Aturan HTML untuk Template

1. **Gunakan inline style** — jangan pakai class CSS, karena PDF engine (dompdf) tidak support CSS eksternal
2. **Ukuran kertas A4** — lebar konten maksimal ~170mm (setelah margin)
3. **Font default** — Times New Roman, 12pt
4. **Tabel** — gunakan `width: 100%` untuk tabel data penduduk
5. **Margin** — gunakan `margin-bottom` untuk jarak antar section
6. **Tanda tangan** — posisi di kanan bawah dengan `text-align: right`
7. **Header** — bisa berupa gambar `<img>` atau HTML kop surat

## Contoh Kop Surat (Header)

```html
<div style="text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px;">
    <img src="[URL_LOGO_DESA]" style="width: 80px; height: 80px;">
    <h2 style="margin: 10px 0 0 0; text-transform: uppercase;">Pemerintah Desa {{desa.nama}}</h2>
    <p style="margin: 2px 0;">Kecamatan {{desa.kecamatan}} - Kabupaten {{desa.kabupaten}}</p>
    <p style="margin: 2px 0;">Provinsi {{desa.provinsi}}</p>
    <p style="margin: 2px 0;">Telp: (0XXX) XXXX-XXXX | Email: desa@domain.go.id</p>
</div>
```

## Contoh Footer (Tanda Tangan)

```html
<div style="margin-top: 60px; text-align: right; padding-right: 40px;">
    <p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p>
    <br><br><br>
    <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">
        {{ttd.nama}}
    </p>
    <p style="margin-top: 0;">{{ttd.jabatan}}</p>
</div>
```

---

## Tips untuk AI

1. **Selalu gunakan placeholder** — jangan hardcode data penduduk
2. **Perhatikan urutan field** — Nama, NIK, Tempat/Tgl Lahir, Jenis Kelamin, Agama, Pekerjaan, Alamat
3. **Gunakan bahasa Indonesia yang baku** — "Yang bertanda tangan di bawah ini", "dengan ini menerangkan bahwa"
4. **Sertakan keperluan** — jika ada field keperluan, masukkan dalam surat
5. **Format tanggal Indonesia** — "5 September 2026" bukan "05/09/2026"
6. **Hindari CSS eksternal** — gunakan inline style saja
7. **Pastikan closing tag HTML lengkap** — `<table>`, `<tr>`, `<td>` harus ditutup
8. **Escape HTML special chars** — jika ada konten user input, pastikan aman dari XSS
