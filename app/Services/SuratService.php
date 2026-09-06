<?php

namespace App\Services;

use App\Models\AdminSetting;
use App\Models\DesaSetting;
use App\Models\Penduduk;
use App\Models\Surat;
use App\Models\SuratTemplate;
use Illuminate\Support\Facades\Storage;

class SuratService
{
    public static array $bulanRomawi = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
        5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
        9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
    ];

    private static array $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /**
     * Generate nomor surat otomatis berdasarkan jenis dan bulan
     */
    public static function generateNomor(SuratTemplate $template, ?string $overrideNomor = null): string
    {
        $overrideNomor = trim((string) $overrideNomor);

        // Jika override diisi, cek apakah hanya angka (nomor urut saja)
        if ($overrideNomor !== '') {
            if (is_numeric($overrideNomor)) {
                // Hanya nomor urut, tetap gunakan format template
                $nomorUrut = str_pad($overrideNomor, 3, '0', STR_PAD_LEFT);
                $kodeJenis = strtoupper(substr($template->slug, 0, 3));
                $bulan = (int) now()->format('m');
                $tahun = now()->format('Y');
                $bulanRomawi = self::$bulanRomawi[$bulan];

                $format = $template->default_nomor_format
                    ?: '{nomor}/SRT/{jenis}/{bulan_romawi}/{tahun}';

                $replacements = [
                    '{nomor}' => $nomorUrut,
                    '{jenis}' => $kodeJenis,
                    '{kode_jenis}' => $kodeJenis,
                    '{bulan_romawi}' => $bulanRomawi,
                    '{bulan}' => $bulan,
                    '{tahun}' => $tahun,
                ];

                return strtr($format, $replacements);
            }

            // Selain angka, anggap nomor manual lengkap
            return $overrideNomor;
        }

        $kodeJenis = strtoupper(substr($template->slug, 0, 3));
        $bulan = (int) now()->format('m');
        $tahun = now()->format('Y');
        $bulanRomawi = self::$bulanRomawi[$bulan];

        // Ambil nomor urut tertinggi dari surat-surat dengan template yang sama
        // pada bulan/tahun ini. Fallback ke jenis_surat jika template tidak punya id.
        $query = Surat::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan);

        if ($template->id) {
            $query->where('surat_template_id', $template->id);
        } else {
            $query->where('jenis_surat', $template->jenis_surat);
        }

        $lastNumber = $query->get()
            ->map(function ($surat) {
                if ($surat->nomor_manual) {
                    return (int) preg_replace('/\D/', '', substr($surat->nomor_manual, 0, 3)) ?: 0;
                }
                return (int) preg_replace('/\D/', '', substr((string) $surat->nomor_surat, 0, 3)) ?: 0;
            })
            ->max();

        $nomorUrut = str_pad(($lastNumber ?? 0) + 1, 3, '0', STR_PAD_LEFT);

        $format = $template->default_nomor_format
            ?: '{nomor}/SRT/{jenis}/{bulan_romawi}/{tahun}';

        $replacements = [
            '{nomor}' => $nomorUrut,
            '{jenis}' => $kodeJenis,
            '{kode_jenis}' => $kodeJenis,
            '{bulan_romawi}' => $bulanRomawi,
            '{bulan}' => $bulan,
            '{tahun}' => $tahun,
        ];

        return strtr($format, $replacements);
    }

    /**
     * Build data array dari penduduk untuk placeholder rendering
     */
    public static function buildDataFromPenduduk(Penduduk $penduduk): array
    {
        $penduduk->load(['kartuKeluarga.kepalaKeluarga', 'kartuKeluarga.rumah.rt.rw', 'rumah.rt.rw']);

        $kk = $penduduk->kartuKeluarga;
        $rumah = $penduduk->rumah ?? $kk?->rumah;
        $rt = $rumah?->rt;
        $rw = $rt?->rw;
        $desa = DesaSetting::get();

        $tanggalLahir = $penduduk->tanggal_lahir;
        $bulanLahir = $tanggalLahir ? (int) $tanggalLahir->format('m') : null;

        return [
            'penduduk' => [
                'nama' => $penduduk->nama,
                'nik' => $penduduk->nik,
                'tempat_lahir' => $penduduk->tempat_lahir,
                'tanggal_lahir' => $tanggalLahir ? $tanggalLahir->format('d/m/Y') : '',
                'tanggal_lahir_panjang' => $tanggalLahir ? $tanggalLahir->format('d') . ' ' . self::$bulanIndo[$bulanLahir] . ' ' . $tanggalLahir->format('Y') : '',
                'jenis_kelamin' => $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                'jenis_kelamin singkat' => $penduduk->jenis_kelamin,
                'agama' => $penduduk->agama ?? '',
                'pekerjaan' => $penduduk->pekerjaan ?? '',
                'pendidikan' => $penduduk->pendidikan_terakhir ?? '',
                'status_kawin' => $penduduk->status_kawin ?? '',
                'umur' => $tanggalLahir ? (int) $tanggalLahir->diffInYears(now()) . ' tahun' : '',
            ],
            'kk' => [
                'no_kk' => $kk?->no_kk ?? '',
                'kepala_keluarga' => $kk?->kepalaKeluarga?->nama ?? '',
                'alamat' => $kk?->alamat ?? '',
            ],
            'rumah' => [
                'kode' => $rumah?->kode_rumah ?? '',
                'alamat' => $rumah?->alamat ?? '',
            ],
            'rt' => $rt?->nama ?? '',
            'rw' => $rw?->nama ?? '',
            'desa' => [
                'nama' => AdminSetting::get('nama_desa', $desa?->nama_desa ?? ''),
                'kecamatan' => AdminSetting::get('nama_kecamatan', ''),
                'kabupaten' => AdminSetting::get('nama_kabupaten', ''),
                'provinsi' => AdminSetting::get('nama_provinsi', ''),
                'alamat' => AdminSetting::get('alamat_desa', ''),
                'kode_pos' => AdminSetting::get('kode_pos', ''),
                'telepon' => AdminSetting::get('telepon', ''),
                'email' => AdminSetting::get('email', ''),
            ],
        ];
    }

    /**
     * Render template HTML dengan data
     */
    public static function renderTemplate(SuratTemplate $template, array $data): string
    {
        $html = $template->body_html;

        // Flatten data for placeholder replacement
        $flat = self::flattenArray($data);

        // Replace placeholders: {{key}}
        foreach ($flat as $key => $value) {
            $html = str_replace('{{' . $key . '}}', (string) $value, $html);
        }

        return $html;
    }

    /**
     * Render surat lengkap dengan kop dan footer
     */
    public static function renderFull(Surat $surat): string
    {
        $template = $surat->template;
        if (!$template) {
            return $surat->draft_html ?? '<p>Template tidak ditemukan</p>';
        }

        $pendudukData = self::buildDataFromPenduduk($surat->penduduk);
        $suratData = [
            'surat' => [
                'nomor' => $surat->nomor_display,
                'tanggal' => $surat->created_at->format('d'),
                'bulan' => self::$bulanIndo[(int) $surat->created_at->format('m')],
                'bulan_romawi' => self::$bulanRomawi[(int) $surat->created_at->format('m')],
                'tahun' => $surat->created_at->format('Y'),
                'jenis' => ucfirst(str_replace('_', ' ', $surat->jenis_surat)),
            ],
            'ttd' => [
                'nama' => AdminSetting::get('ttd_kades_nama', ''),
                'jabatan' => AdminSetting::get('ttd_kades_jabatan', 'Kepala Desa'),
            ],
        ];

        $allData = array_merge($pendudukData, $suratData);
        $body = self::renderTemplate($template, $allData);

        $header = $template->header_html ?? '';
        $footer = $template->footer_html ?? '';

        // Apply data to header and footer too
        $flat = self::flattenArray($allData);
        foreach ($flat as $key => $value) {
            $header = str_replace('{{' . $key . '}}', (string) $value, $header);
            $footer = str_replace('{{' . $key . '}}', (string) $value, $footer);
        }

        return $header . $body . $footer;
    }

    /**
     * Generate PDF dari surat
     */
    public static function generatePdf(Surat $surat): string
    {
        $html = self::embedLocalImages(self::renderFull($surat));

        // F4 = 215mm × 330mm, dinyatakan dalam poin (1 mm = 72/25.4 pt).
        $paper = strtolower($surat->paper_size ?? 'a4') === 'f4'
            ? [0.0, 0.0, 609.45, 935.43]
            : 'a4';

        $pdf = app(\Barryvdh\DomPDF\PDF::class)
            ->loadHtml($html)
            ->setPaper($paper, 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $year = $surat->created_at->format('Y');
        $filename = str_replace('/', '-', $surat->nomor_display) . '.pdf';
        $path = "surat/{$year}/{$filename}";

        Storage::disk('local')->makeDirectory("surat/{$year}");
        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Embed gambar lokal (path /storage/...) sebagai data URI agar DomPDF
     * tidak perlu memanggil server sendiri (menghindari deadlock saat
     * php artisan serve yang single-process).
     */
    private static function embedLocalImages(string $html): string
    {
        return preg_replace_callback('/<img\b[^>]*\bsrc\s*=\s*["\']([^"\']+)["\'][^>]*>/i', function ($matches) {
            $tag = $matches[0];
            $src = html_entity_decode($matches[1]);

            $urlPath = parse_url($src, PHP_URL_PATH) ?: $src;
            if (!str_starts_with($urlPath, '/storage/')) {
                return $tag;
            }

            $relative = substr($urlPath, strlen('/storage/'));
            $candidates = [
                public_path('storage/' . $relative),
                storage_path('app/public/' . $relative),
            ];

            foreach ($candidates as $file) {
                if (is_file($file)) {
                    $mime = mime_content_type($file) ?: 'image/png';
                    $data = base64_encode(file_get_contents($file));
                    $dataUri = 'data:' . $mime . ';base64,' . $data;

                    return str_replace($matches[1], $dataUri, $tag);
                }
            }

            return $tag;
        }, $html);
    }

    /**
     * Ambil path PDF surat; generate dulu jika belum ada.
     * Dipakai untuk menampilkan PDF inline (buka di viewer), bukan download.
     */
    public static function getOrCreatePdf(Surat $surat): string
    {
        $year = $surat->created_at->format('Y');
        $filename = str_replace('/', '-', $surat->nomor_display) . '.pdf';
        $path = "surat/{$year}/{$filename}";

        if (Storage::disk('local')->exists($path)) {
            return $path;
        }

        return self::generatePdf($surat);
    }

    /**
     * Hapus file PDF surat jika ada
     */
    public static function deletePdf(Surat $surat): void
    {
        $year = $surat->created_at->format('Y');
        $filename = str_replace('/', '-', $surat->nomor_display) . '.pdf';

        Storage::disk('local')->delete("surat/{$year}/{$filename}");
    }

    /**
     * Log pencetakan surat
     */
    public static function logPrint(Surat $surat, int $userId, string $format = 'pdf'): void
    {
        $surat->printLogs()->create([
            'user_id' => $userId,
            'printed_at' => now(),
            'format' => $format,
        ]);
    }

    /**
     * Flatten nested array for placeholder replacement
     * penduduk.nama → "penduduk.nama"
     */
    private static function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            if (is_array($value)) {
                $result = array_merge($result, self::flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }
}
