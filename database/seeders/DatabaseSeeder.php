<?php

namespace Database\Seeders;

use App\Models\FasilitasUmum;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Pengumuman;
use App\Models\Rt;
use App\Models\Rumah;
use App\Models\Rw;
use App\Models\AdminSetting;
use App\Models\Surat;
use App\Models\SuratTemplate;
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

        // RW & RT
        $allRts = [];
        for ($rw = 1; $rw <= 3; $rw++) {
            $rwModel = Rw::create(['nama' => 'RW ' . $rw]);
            for ($rt = 1; $rt <= 3; $rt++) {
                $allRts[] = Rt::create([
                    'rw_id' => $rwModel->id,
                    'nama' => 'RT ' . $rt,
                ]);
            }
        }

        // Rumah
        $rumahs = [];
        for ($i = 1; $i <= 20; $i++) {
            $rt = $allRts[($i - 1) % count($allRts)];
            $rumahs[] = Rumah::create([
                'kode_rumah' => 'R' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'rt_id' => $rt->id,
                'alamat' => 'Jl. Contoh No. ' . $i,
                'posisi' => (-6.9 + (rand(-100, 100) / 10000)) . ', ' . (107.6 + (rand(-100, 100) / 10000)),
                'punya_mck' => rand(0, 1),
                'kategori_rtlh' => rand(0, 3) === 0 ? 'tidak_layak' : 'layak',
            ]);
        }

        // Fasilitas Umum
        FasilitasUmum::create([
            'nama' => 'Masjid Al-Ikhlas',
            'jenis' => 'Masjid',
            'rt_id' => $allRts[0]->id,
            'posisi' => '-6.905, 107.605',
        ]);

        FasilitasUmum::create([
            'nama' => 'SD Negeri 1',
            'jenis' => 'Sekolah',
            'rt_id' => $allRts[3]->id,
            'posisi' => '-6.910, 107.610',
        ]);

        FasilitasUmum::create([
            'nama' => 'Puskesmas Pembantu',
            'jenis' => 'Puskesmas',
            'rt_id' => $allRts[1]->id,
            'posisi' => '-6.908, 107.608',
        ]);

        // Penduduk & KK
        $namaLaki = ['Ahmad', 'Budi', 'Dedi', 'Eko', 'Fajar', 'Gilang', 'Hadi', 'Indra', 'Joko', 'Kurniawan'];
        $namaPerempuan = ['Siti', 'Dewi', 'Fitri', 'Gita', 'Hana', 'Indah', 'Juli', 'Kartika', 'Lestari', 'Maya'];

        for ($i = 1; $i <= 30; $i++) {
            $isLaki = $i <= 20;
            $nama = $isLaki ? $namaLaki[($i - 1) % 10] . ' ' . $i : $namaPerempuan[($i - 21) % 10] . ' ' . $i;

            // Status KK: setiap 3 penduduk = 1 keluarga (suami, istri, anak)
            $posisiDalamKeluarga = ($i - 1) % 3;
            $statusKk = ['suami', 'istri', 'anak'][$posisiDalamKeluarga];

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
                'status_kk' => $statusKk,
            ]);

            if ($i % 3 === 1) {
                $kk = KartuKeluarga::create([
                    'no_kk' => str_pad(3200000000000000 + $i, 16, '0', STR_PAD_LEFT),
                    'kepala_keluarga_id' => $penduduk->id,
                    'rumah_id' => $rumahs[($i - 1) / 3]->id,
                    'alamat' => 'Jl. Contoh No. ' . (($i - 1) / 3 + 1),
                ]);

                $penduduk->update(['kartu_keluarga_id' => $kk->id]);
            } else {
                // Anggota keluarga ikut KK kepala keluarga
                $kkIndex = intdiv($i - 1, 3);
                $kepalaIndex = $kkIndex * 3 + 1;
                $kk = KartuKeluarga::where('kepala_keluarga_id', function ($q) use ($kepalaIndex) {
                    $q->select('id')->from('penduduks')->where('nik', str_pad(3200000000000000 + $kepalaIndex, 16, '0', STR_PAD_LEFT));
                })->first();
                if ($kk) {
                    $penduduk->update(['kartu_keluarga_id' => $kk->id]);
                }
            }
        }

        // Admin Settings (info desa untuk surat)
        AdminSetting::set('nama_desa', 'Desa Waeleman');
        AdminSetting::set('nama_kecamatan', 'Kecamatan Waeleman');
        AdminSetting::set('nama_kabupaten', 'Kabupaten Ende');
        AdminSetting::set('nama_provinsi', 'NTT');
        AdminSetting::set('alamat_desa', '');
        AdminSetting::set('kode_pos', '');
        AdminSetting::set('telepon', '');
        AdminSetting::set('email', '');
        AdminSetting::set('ttd_kades_nama', 'Budi Santoso');
        AdminSetting::set('ttd_kades_jabatan', 'Kepala Desa Waeleman');
        AdminSetting::set('ttd_sekdes_nama', 'Andi Cahyadi');
        AdminSetting::set('ttd_sekdes_jabatan', 'Sekretaris Desa');

        // Surat Templates
        $admin = User::where('email', 'admin@sidesa.id')->first();

        SuratTemplate::create([
            'nama' => 'Surat Keterangan Domisili',
            'slug' => 'domisili',
            'jenis_surat' => 'domisili',
            'body_html' => '<div style="text-align: center; margin-bottom: 30px;"><h2 style="margin: 0;">SURAT KETERANGAN DOMISILI</h2><p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p></div><p>Yang bertanda tangan di bawah ini, Kepala Desa Waeleman, Kecamatan Waeleman, Kabupaten Ende, dengan ini menerangkan bahwa:</p><table style="width: 100%; margin: 20px 0;"><tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr><tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr><tr><td>Tempat / Tgl Lahir</td><td>: {{penduduk.tempat_lahir}}, {{penduduk.tanggal_lahir_panjang}}</td></tr><tr><td>Jenis Kelamin</td><td>: {{penduduk.jenis_kelamin}}</td></tr><tr><td>Agama</td><td>: {{penduduk.agama}}</td></tr><tr><td>Pekerjaan</td><td>: {{penduduk.pekerjaan}}</td></tr><tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa Waeleman</td></tr></table><p>Bahwa yang bersangkutan berdomisili di wilayah Desa Waeleman dan merupakan penduduk yang sah.</p><p>Surat keterangan ini dibuat untuk keperluan: <strong>{{penduduk.nama}}</strong></p><div style="margin-top: 60px; text-align: right;"><p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p><p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">{{ttd.nama}}</p><p>{{ttd.jabatan}}</p></div>',
            'is_active' => true,
            'created_by' => $admin?->id,
        ]);

        SuratTemplate::create([
            'nama' => 'Surat Keterangan Tidak Mampu',
            'slug' => 'tidak-mampu',
            'jenis_surat' => 'tidak_mampu',
            'body_html' => '<div style="text-align: center; margin-bottom: 30px;"><h2 style="margin: 0;">SURAT KETERANGAN TIDAK MAMPU</h2><p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p></div><p>Yang bertanda tangan di bawah ini, Kepala Desa Waeleman, dengan ini menerangkan bahwa:</p><table style="width: 100%; margin: 20px 0;"><tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr><tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr><tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa Waeleman</td></tr></table><p>Bahwa yang bersangkutan tergolong keluarga tidak mampu dan membutuhkan bantuan.</p><div style="margin-top: 60px; text-align: right;"><p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p><p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">{{ttd.nama}}</p><p>{{ttd.jabatan}}</p></div>',
            'is_active' => true,
            'created_by' => $admin?->id,
        ]);

        SuratTemplate::create([
            'nama' => 'Surat Pengantar KTP/KK',
            'slug' => 'pengantar-ktp-kk',
            'jenis_surat' => 'pengantar_ktp_kk',
            'body_html' => '<div style="text-align: center; margin-bottom: 30px;"><h2 style="margin: 0;">SURAT PENGANTAR</h2><p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p></div><p>Yang bertanda tangan di bawah ini, Kepala Desa Waeleman, dengan ini menerangkan bahwa:</p><table style="width: 100%; margin: 20px 0;"><tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr><tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr><tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa Waeleman</td></tr></table><p>Adalah penduduk Desa Waeleman yang telah memenuhi syarat untuk mengurus pembuatan/KTP/KK.</p><div style="margin-top: 60px; text-align: right;"><p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p><p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">{{ttd.nama}}</p><p>{{ttd.jabatan}}</p></div>',
            'is_active' => true,
            'created_by' => $admin?->id,
        ]);

        SuratTemplate::create([
            'nama' => 'Surat Keterangan Usaha',
            'slug' => 'usaha',
            'jenis_surat' => 'usaha',
            'body_html' => '<div style="text-align: center; margin-bottom: 30px;"><h2 style="margin: 0;">SURAT KETERANGAN USAHA</h2><p style="margin: 5px 0 0 0;">Nomor: {{surat.nomor}}</p></div><p>Yang bertanda tangan di bawah ini, Kepala Desa Waeleman, dengan ini menerangkan bahwa:</p><table style="width: 100%; margin: 20px 0;"><tr><td style="width: 200px;">Nama</td><td>: {{penduduk.nama}}</td></tr><tr><td>NIK</td><td>: {{penduduk.nik}}</td></tr><tr><td>Pekerjaan</td><td>: {{penduduk.pekerjaan}}</td></tr><tr><td>Alamat</td><td>: RT {{rt}} / RW {{rw}}, Desa Waeleman</td></tr></table><p>Bahwa yang bersangkutan memiliki usaha di wilayah Desa Waeleman.</p><div style="margin-top: 60px; text-align: right;"><p>{{desa.nama}}, {{surat.tanggal}} {{surat.bulan}} {{surat.tahun}}</p><p style="margin-top: 50px; text-decoration: underline; font-weight: bold;">{{ttd.nama}}</p><p>{{ttd.jabatan}}</p></div>',
            'is_active' => true,
            'created_by' => $admin?->id,
        ]);

        // Surat (legacy format)
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
