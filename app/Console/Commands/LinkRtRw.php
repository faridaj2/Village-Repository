<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rumah;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;

class LinkRtRw extends Command
{
    protected $signature = 'link:rt-rw';
    protected $description = 'Link semua KK dan penduduk ke RT 1 RW 1';

    public function handle()
    {
        $rtId = 1; // RT 1 di RW 1
        $count = 0;

        foreach (KartuKeluarga::all() as $kk) {
            $rumah = Rumah::create([
                'rt_id' => $rtId,
                'alamat' => 'WAELEMAN',
            ]);

            $kk->update(['rumah_id' => $rumah->id]);
            Penduduk::where('kartu_keluarga_id', $kk->id)->update(['rumah_id' => $rumah->id]);
            $count++;
        }

        $this->info("Selesai! {$count} rumah dibuat dan di-link ke RT 1 RW 1.");
        $this->info("KK terlink: " . KartuKeluarga::whereNotNull('rumah_id')->count());
        $this->info("Penduduk terlink: " . Penduduk::whereNotNull('rumah_id')->count());

        return 0;
    }
}
