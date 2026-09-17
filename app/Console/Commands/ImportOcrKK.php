<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;

class ImportOcrKK extends Command
{
    protected $signature = 'import:ocr-kk {file=Hasil_OCR_Kartu_Keluarga.json}';
    protected $description = 'Import data OCR Kartu Keluarga ke database';

    public function handle()
    {
        $file = base_path($this->argument('file'));
        if (!file_exists($file)) {
            $this->error("File tidak ditemukan: $file");
            return 1;
        }

        $json = json_decode(file_get_contents($file), true);
        $data = $json['data_kartu_keluarga'];

        $this->info("Mengimport {$json['jumlah_kk']} KK dengan {$json['jumlah_anggota_total']} anggota...");

        $bar = $this->output->createProgressBar(count($data));
        $bar->start();

        $skipped = 0;

        DB::transaction(function () use ($data, $bar, &$skipped) {
            foreach ($data as $kk) {
                $kartuKeluarga = KartuKeluarga::create([
                    'no_kk' => $kk['no_kk'],
                    'alamat' => null,
                ]);

                $kepalaId = null;

                foreach ($kk['anggota_keluarga'] as $anggota) {
                    if (Penduduk::where('nik', $anggota['nik'])->exists()) {
                        $skipped++;
                        continue;
                    }

                    $tglLahir = $this->parseDate($anggota['tanggal_lahir']);
                    $jk = $anggota['jenis_kelamin'] === 'LAKI-LAKI' ? 'L' : 'P';
                    $statusKk = $this->mapStatusKk($anggota['status_hubungan_dalam_keluarga'], $jk);

                    $penduduk = Penduduk::create([
                        'nik' => $anggota['nik'],
                        'nama' => $anggota['nama_lengkap'],
                        'tempat_lahir' => $anggota['tempat_lahir'] ?: null,
                        'tanggal_lahir' => $tglLahir,
                        'jenis_kelamin' => $jk,
                        'agama' => $anggota['agama'] ?: null,
                        'pendidikan_terakhir' => $anggota['pendidikan'] ?: null,
                        'pekerjaan' => $anggota['jenis_pekerjaan'] ?: null,
                        'status_kawin' => $anggota['status_perkawinan'] ?: null,
                        'kartu_keluarga_id' => $kartuKeluarga->id,
                        'status_kk' => $statusKk,
                        'status' => 'aktif',
                        'jenis_penduduk' => 'penduduk',
                    ]);

                    if ($statusKk === 'suami' && $kepalaId === null) {
                        $kepalaId = $penduduk->id;
                    }
                }

                if ($kepalaId) {
                    $kartuKeluarga->update(['kepala_keluarga_id' => $kepalaId]);
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Import selesai! {$skipped} anggota dilewati (duplikat NIK).");
        return 0;
    }

    private function parseDate(string $date): ?string
    {
        if (empty($date)) return null;
        $parts = explode('-', $date);
        if (count($parts) !== 3) return null;
        return "{$parts[2]}-{$parts[1]}-{$parts[0]}";
    }

    private function mapStatusKk(string $status, string $jk): string
    {
        return match ($status) {
            'KEPALA KELUARGA' => $jk === 'L' ? 'suami' : 'istri',
            'ISTRI' => 'istri',
            'ANAK' => 'anak',
            default => 'anak',
        };
    }
}
