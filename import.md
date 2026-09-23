# Import Data Kartu Keluarga

Fitur untuk memasukkan data hasil OCR Kartu Keluarga ke database.

## File yang Terlibat

- `app/Console/Commands/ImportOcrKK.php` — Command artisan `import:ocr-kk`
- `app/Console/Commands/LinkRtRw.php` — Command artisan `link:rt-rw`

## Struktur JSON Input

```json
{
  "data_kartu_keluarga": [
    {
      "no_kk": "16 digit",
      "anggota_keluarga": [
        {
          "nik": "16 digit",
          "nama_lengkap": "",
          "jenis_kelamin": "LAKI-LAKI | PEREMPUAN",
          "tempat_lahir": "",
          "tanggal_lahir": "DD-MM-YYYY",
          "agama": "",
          "pendidikan": "",
          "jenis_pekerjaan": "",
          "status_perkawinan": "",
          "status_hubungan_dalam_keluarga": "KEPALA KELUARGA | ISTRI | ANAK"
        }
      ]
    }
  ]
}
```

## Mapping Field JSON → Database

### Tabel `kartu_keluargas`
| JSON | Database |
|------|----------|
| `no_kk` | `no_kk` |
| — | `alamat` (null) |
| — | `rumah_id` (diisi oleh `link:rt-rw`) |
| — | `kepala_keluarga_id` (diisi otomatis dari anggota KEPALA KELUARGA) |

### Tabel `penduduks`
| JSON | Database | Catatan |
|------|----------|---------|
| `nik` | `nik` | Unique, duplikat di-skip |
| `nama_lengkap` | `nama` | |
| `tempat_lahir` | `tempat_lahir` | |
| `tanggal_lahir` | `tanggal_lahir` | DD-MM-YYYY → YYYY-MM-DD |
| `jenis_kelamin` | `jenis_kelamin` | LAKI-LAKI→L, PEREMPUAN→P |
| `agama` | `agama` | |
| `pendidikan` | `pendidikan_terakhir` | |
| `jenis_pekerjaan` | `pekerjaan` | |
| `status_perkawinan` | `status_kawin` | |
| `status_hubungan_dalam_keluarga` | `status_kkk` | KEPALA KELUARGA+L→suami, KEPALA KELUARGA+P→istri, ISTRI→istri, ANAK→anak |
| — | `status` | Default `aktif` |
| — | `jenis_penduduk` | Default `penduduk` |

## Cara Menggunakan

### 1. Import data KK dan anggota

```bash
# Dari file default (Hasil_OCR_Kartu_Keluarga.json)
php artisan import:ocr-kk

# Dari file tertentu
php artisan import:ocr-kk nama_file.json
```

Command ini akan:
- Membuat record `kartu_keluargas` per KK
- Membuat record `penduduks` per anggota
- Meng-link `kepala_keluarga_id` pada KK
- Melewati NIK yang sudah ada (duplikat)

### 2. Link ke RT/RW

```bash
php artisan link:rt-rw
```

Command ini akan:
- Membuat 1 record `rumahs` per KK di RT 1 (bisa diubah di kode, `$rtId`)
- Mengisi `rumah_id` pada KK
- **Tidak** mengisi `rumah_id` pada penduduk (link hanya via KK)

### 3. Alur lengkap

```bash
php artisan import:ocr-kk data_baru.json
php artisan link:rt-rw
```

## Catatan

- Jika ada NIK duplikat (orang yang sama muncul di 2 KK, misalnya pindah setelah menikah), data di-skip dari KK kedua
- RT/RW bisa diubah dengan mengedit nilai `$rtId` di `LinkRtRw.php`
- Rumah yang dibuat bersifat minimal (`rt_id` + `alamat` saja)
- `link:rt-rw` akan membuat rumah baru untuk **semua** KK yang belum punya `rumah_id`
