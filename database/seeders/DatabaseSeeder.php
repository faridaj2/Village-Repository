<?php

namespace Database\Seeders;

use App\Models\Dusun;
use App\Models\FasilitasUmum;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Pengumuman;
use App\Models\Rumah;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sidesa.id',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Operator',
            'email' => 'operator@sidesa.id',
            'password' => 'password',
            'role' => 'operator',
        ]);

        User::create([
            'name' => 'Kepala Dusun 1',
            'email' => 'kadus1@sidesa.id',
            'password' => 'password',
            'role' => 'kadus',
        ]);

        // Dusun
        $dusun1 = Dusun::create(['nama' => 'Dusun 1']);
        $dusun2 = Dusun::create(['nama' => 'Dusun 2']);
        $dusun3 = Dusun::create(['nama' => 'Dusun 3']);

        // Rumah
        $rumahs = [];
        for ($i = 1; $i <= 20; $i++) {
            $dusun = [$dusun1, $dusun2, $dusun3][($i - 1) % 3];
            $rumahs[] = Rumah::create([
                'kode_rumah' => 'R' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'dusun_id' => $dusun->id,
                'alamat' => 'Jl. Contoh No. ' . $i,
                'latitude' => -6.9 + (rand(-100, 100) / 10000),
                'longitude' => 107.6 + (rand(-100, 100) / 10000),
                'status_kepemilikan' => ['milik_sendiri', 'kontrak', 'keluarga'][rand(0, 2)],
                'jenis_lantai' => ['keramik', 'semen', 'tanah'][rand(0, 2)],
                'jenis_dinding' => ['tembok', 'kayu', 'bambu'][rand(0, 2)],
                'jenis_atap' => ['genteng', 'seng', 'asbes'][rand(0, 2)],
                'sumber_air' => ['pdam', 'sumur', 'mata_air'][rand(0, 2)],
                'punya_mck' => rand(0, 1),
                'sumber_listrik' => ['pln', 'non_pln'][rand(0, 1)],
                'kategori_rtlh' => rand(0, 3) === 0 ? 'tidak_layak' : 'layak',
                'luas_bangunan' => rand(30, 100),
                'luas_tanah' => rand(50, 200),
            ]);
        }

        // Fasilitas Umum
        FasilitasUmum::create([
            'nama' => 'Masjid Al-Ikhlas',
            'jenis' => 'Masjid',
            'dusun_id' => $dusun1->id,
            'latitude' => -6.905,
            'longitude' => 107.605,
        ]);

        FasilitasUmum::create([
            'nama' => 'SD Negeri 1',
            'jenis' => 'Sekolah',
            'dusun_id' => $dusun2->id,
            'latitude' => -6.910,
            'longitude' => 107.610,
        ]);

        FasilitasUmum::create([
            'nama' => 'Puskesmas Pembantu',
            'jenis' => 'Puskesmas',
            'dusun_id' => $dusun1->id,
            'latitude' => -6.908,
            'longitude' => 107.608,
        ]);

        // Penduduk & KK
        $namaLaki = ['Ahmad', 'Budi', 'Dedi', 'Eko', 'Fajar', 'Gilang', 'Hadi', 'Indra', 'Joko', 'Kurniawan'];
        $namaPerempuan = ['Siti', 'Dewi', 'Fitri', 'Gita', 'Hana', 'Indah', 'Juli', 'Kartika', 'Lestari', 'Maya'];

        for ($i = 1; $i <= 30; $i++) {
            $isLaki = $i <= 20;
            $nama = $isLaki ? $namaLaki[($i - 1) % 10] . ' ' . $i : $namaPerempuan[($i - 21) % 10] . ' ' . $i;

            $penduduk = Penduduk::create([
                'nik' => str_pad(3200000000000000 + $i, 16, '0', STR_PAD_LEFT),
                'nama' => $nama,
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => now()->subYears(rand(5, 60))->subDays(rand(0, 365)),
                'jenis_kelamin' => $isLaki ? 'L' : 'P',
                'agama' => 'Islam',
                'pendidikan_terakhir' => ['SD', 'SMP', 'SMA', 'S1'][rand(0, 3)],
                'pekerjaan' => ['Petani', 'Buruh', 'Wiraswasta', 'PNS'][rand(0, 3)],
                'status_kawin' => ['belum_kawin', 'kawin'][rand(0, 1)],
                'status' => 'aktif',
            ]);

            // Create KK for every 3rd penduduk
            if ($i % 3 === 1) {
                $kk = KartuKeluarga::create([
                    'no_kk' => str_pad(3200000000000000 + $i, 16, '0', STR_PAD_LEFT),
                    'kepala_keluarga_id' => $penduduk->id,
                    'rumah_id' => $rumahs[($i - 1) / 3]->id,
                    'alamat' => 'Jl. Contoh No. ' . (($i - 1) / 3 + 1),
                ]);

                $penduduk->update(['kartu_keluarga_id' => $kk->id]);
            }
        }

        // Surat
        $jenisSurat = ['domisili', 'tidak_mampu', 'usaha', 'pengantar_ktp_kk'];
        $statusSurat = ['diajukan', 'diproses', 'selesai', 'ditolak'];

        for ($i = 1; $i <= 10; $i++) {
            Surat::create([
                'nomor_tracking' => 'SRT-' . strtoupper(uniqid()),
                'penduduk_id' => rand(1, 30),
                'jenis_surat' => $jenisSurat[rand(0, 3)],
                'data_tambahan' => ['keperluan' => 'Keperluan surat nomor ' . $i],
                'status' => $statusSurat[rand(0, 3)],
                'catatan_admin' => $i % 4 === 0 ? 'Dokumen tidak lengkap' : null,
            ]);
        }

        // Pengumuman
        Pengumuman::create([
            'judul' => 'Pembagian BLT Bulan Ini',
            'isi' => 'Pembagian BLT akan dilaksanakan pada hari Senin, tanggal 15 Januari 2024 di Balai Desa. Warga yang berhak mohon hadir tepat waktu dengan membawa KTP asli.',
            'kategori' => 'Bansos',
            'tanggal_publish' => now()->subDays(5),
            'is_published' => true,
        ]);

        Pengumuman::create([
            'judul' => 'Kerja Bakti Minggu Pagi',
            'isi' => 'Mengundang seluruh warga untuk berpartisipasi dalam kerja bakti pada hari Minggu pukul 07.00 WIB. Titik kumpul di depan Balai Desa.',
            'kategori' => 'Kegiatan',
            'tanggal_publish' => now()->subDays(2),
            'is_published' => true,
        ]);

        Pengumuman::create([
            'judul' => 'Jadwal Posyandu Bulan Januari',
            'isi' => 'Posyandu akan dilaksanakan setiap hari Rabu minggu kedua dan keempat. Ibu-ibu yang memiliki balita diharapkan datang ke Posyandu terdekat.',
            'kategori' => 'Kesehatan',
            'tanggal_publish' => now(),
            'is_published' => true,
        ]);
    }
}
