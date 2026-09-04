# PRD — SIDESA (Sistem Informasi Desa)

## 1. Ringkasan Produk

Aplikasi web untuk membantu perangkat desa mengelola data kependudukan, rumah warga, peta desa, dan pelayanan administrasi, sekaligus memberi warga akses informasi dan layanan pengajuan surat secara online.

**Masalah yang diselesaikan:**
- Data penduduk & rumah masih manual (kertas/Excel), sulit dicari dan rawan hilang.
- Warga harus datang ke kantor desa untuk hal-hal yang sebenarnya bisa online (cek status surat, lihat pengumuman).
- Tidak ada visualisasi peta yang menghubungkan data penduduk/rumah dengan lokasi fisik.
- Transparansi anggaran desa minim.

**Target pengguna:**
1. **Perangkat desa (admin)** — Kepala Desa, Sekretaris, Kaur, Kepala Dusun, Operator. Pengguna utama, akses penuh sesuai peran.
2. **Warga (publik)** — akses terbatas: lihat pengumuman, ajukan surat, cek status, kirim pengaduan. *(Asumsi: warga tidak perlu login penuh di v1, cukup input NIK + verifikasi sederhana untuk ajukan surat — bisa didiskusikan lagi.)*

## 2. Tujuan v1 (In Scope)

- [ ] Manajemen data penduduk & Kartu Keluarga (KK)
- [ ] Manajemen data rumah, terhubung ke KK
- [ ] Peta desa interaktif (titik rumah, fasilitas umum, batas dusun)
- [ ] Layanan surat online (pengajuan, tracking status, cetak PDF)
- [ ] Pengumuman/berita desa
- [ ] Manajemen user & role perangkat desa
- [ ] Dashboard statistik (jumlah penduduk per usia/pekerjaan/pendidikan, jumlah rumah per kategori RTLH)

## 3. Di Luar Scope v1 (Later)

- Transparansi APBDes (anggaran) — v2
- Data UMKM/BUMDes — v2
- Kotak pengaduan warga — v2
- Notifikasi darurat/bencana — v2
- Aplikasi mobile native — belum, web dulu (mobile-responsive)
- Integrasi langsung ke Dukcapil/kecamatan — belum, export manual dulu

## 4. Modul & Fitur Detail

### 4.1 Kependudukan
| Fitur | Deskripsi |
|---|---|
| CRUD Penduduk | NIK, nama, tempat/tgl lahir, jenis kelamin, agama, pendidikan, pekerjaan, status kawin, alamat |
| CRUD Kartu Keluarga | No. KK, kepala keluarga, anggota keluarga (relasi ke Penduduk) |
| Penduduk Tanpa KK | Mendata warga yang sudah menetap tapi belum memiliki KK atau belum terdata — bisa diinput tanpa relasi KK, dengan status khusus (menunggu KK / belum terverifikasi) |
| Riwayat mutasi | Lahir, meninggal, pindah, datang |
| Klasifikasi khusus | Disabilitas, lansia, kategori kemiskinan (untuk data bansos) |
| Export data | Excel/PDF, format siap lapor ke kecamatan |

**Acceptance criteria:**
- NIK harus unik 16 digit, tervalidasi saat input.
- Satu penduduk hanya boleh terdaftar di satu KK aktif.
- Admin bisa filter/cari penduduk by nama, NIK, dusun, RT/RW.
- Penduduk tanpa KK tetap bisa didata dan dicari; status khususnya ditampilkan jelas di daftar penduduk (badge "Tanpa KK").

### 4.2 Rumah Penduduk
| Fitur | Deskripsi |
|---|---|
| CRUD Rumah | Kode rumah, alamat, koordinat GPS, foto, status kepemilikan |
| Relasi ke KK | Satu rumah bisa punya 1+ KK penghuni |
| Kondisi fisik | Lantai, dinding, atap, sumber air, MCK, listrik |
| Kategori RTLH | Layak huni / tidak layak huni (otomatis dihitung dari kondisi fisik, atau input manual) |

**Acceptance criteria:**
- Rumah tanpa koordinat tetap bisa disimpan, tapi tidak muncul di peta sampai koordinat diisi.
- Kategori RTLH bisa difilter untuk laporan bantuan rumah.

### 4.3 Peta Desa
| Fitur | Deskripsi |
|---|---|
| Peta interaktif | Leaflet, base map OpenStreetMap |
| Titik rumah | Klik marker → muncul info rumah + penghuni |
| Titik fasilitas umum | Masjid, sekolah, puskesmas, kantor desa, dll (CRUD terpisah) |
| Layer batas dusun | Upload/gambar polygon batas wilayah (GeoJSON) |
| Filter peta | By dusun, by kategori RTLH |

### 4.4 Layanan Surat
| Fitur | Deskripsi |
|---|---|
| Pengajuan online | Warga isi form (pilih jenis surat, isi data), dapat nomor tracking |
| Jenis surat awal | Surat domisili, surat tidak mampu, surat usaha, pengantar KTP/KK |
| Tracking status | Diajukan → Diproses → Selesai/Ditolak |
| Admin proses | Perangkat desa verifikasi data, generate PDF surat siap cetak/TTD |

### 4.5 Pengumuman
- Admin post pengumuman (judul, isi, tanggal, kategori).
- Warga lihat di halaman publik tanpa login.

### 4.6 User & Role Management
| Role | Akses |
|---|---|
| Super Admin (Kepala Desa/Sekdes) | Semua akses |
| Operator | CRUD penduduk, rumah, surat |
| Kepala Dusun | View & edit data dusun sendiri saja |
| Warga (guest) | Ajukan surat, lihat pengumuman, tracking |

### 4.7 Dashboard
- Statistik penduduk (grafik usia, pendidikan, pekerjaan, jenis kelamin)
- Statistik rumah (jumlah per kategori RTLH, per dusun)
- Statistik surat (jumlah pengajuan per bulan, per jenis)

## 5. Non-Functional Requirements

- **Keamanan data:** NIK & data pribadi hanya bisa diakses role yang berwenang; tidak ditampilkan di halaman publik.
- **Performa:** Target < 2 detik load untuk halaman list data (dengan pagination).
- **Skala:** Didesain untuk desa dengan ±1.000–5.000 penduduk, tidak perlu arsitektur besar.
- **Aksesibilitas:** Mobile-responsive, karena banyak warga & bahkan perangkat desa akan akses dari HP.
- **Backup:** Data perlu bisa di-backup manual (export DB) secara berkala.

## 6. Metrik Keberhasilan

- Semua data penduduk & rumah desa ter-input dalam sistem (target 100% dalam periode migrasi data).
- Pengurangan waktu proses surat dari manual (hari) ke online (jam).
- Perangkat desa bisa generate laporan/statistik tanpa hitung manual.

## 7. Fitur Tambahan — Penduduk Tanpa KK

**Konteks:** Di desa sering ada warga yang sudah menetap tapi belum memiliki Kartu Keluarga, misalnya:
- Pendatang baru yang belum pindah KK
- Warga yang KK-nya hilang/rusak tapi belum mengurus
- Anak yang belum masuk KK manapun

**Fitur:**
- Form input penduduk tetap bisa diisi meskipun field KK dikosongkan
- Penduduk tanpa KK ditandai badge khusus (misal: oranye "Tanpa KK")
- Filter khusus untuk melihat semua penduduk tanpa KK
- Ketika penduduk sudah punya KK (atau KK baru jadi), admin bisa assign ke KK yang sudah ada di sistem
- Riwayat perubahan status KK (dari null → terdaftar) tercatat untuk audit

## 8. Open Questions

- Apakah warga perlu akun login (email/HP) atau cukup input NIK + verifikasi tanpa akun?
- Format cetak surat resmi desa (kop surat, tanda tangan digital?) — perlu contoh dari desa terkait.
- Siapa yang input data awal (migrasi data penduduk existing)? Perlu fitur import Excel massal?
