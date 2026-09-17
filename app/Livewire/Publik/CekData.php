<?php

namespace App\Livewire\Publik;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Livewire\Component;

class CekData extends Component
{
    public string $tipe = 'nama';
    public string $keyword = '';
    public array $results = [];
    public ?string $detail = null;
    public bool $searched = false;

    public function updatedTipe(): void
    {
        $this->reset(['keyword', 'results', 'searched', 'detail']);
    }

    public function search(): void
    {
        $this->validate([
            'tipe' => 'required|in:nama,nik,kk',
            'keyword' => 'required|string|min:3|max:100',
        ], [
            'keyword.required' => 'Masukkan kata kunci pencarian.',
            'keyword.min' => 'Minimal 3 karakter.',
        ]);

        $this->searched = true;
        $this->detail = null;
        $keyword = trim($this->keyword);

        if ($this->tipe === 'nama') {
            $this->results = Penduduk::query()
                ->where('status', 'aktif')
                ->where('nama', 'like', '%'.$keyword.'%')
                ->with('kartuKeluarga.rumah.rt.rw')
                ->orderBy('nama')
                ->limit(25)
                ->get()
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'nik' => $this->mask($p->nik),
                    'kk' => $p->kartuKeluarga ? $this->mask($p->kartuKeluarga->no_kk) : '—',
                    'jk' => $p->jenis_kelamin,
                    'alamat' => $this->alamatRingkas($p),
                ])
                ->toArray();
        } elseif ($this->tipe === 'nik') {
            $p = Penduduk::query()
                ->where('nik', $keyword)
                ->with('kartuKeluarga.rumah.rt.rw')
                ->first();

            $this->results = $p ? [[
                'id' => $p->id,
                'nama' => $p->nama,
                'nik' => $this->mask($p->nik),
                'kk' => $p->kartuKeluarga ? $this->mask($p->kartuKeluarga->no_kk) : '—',
                'jk' => $p->jenis_kelamin,
                'alamat' => $this->alamatRingkas($p),
            ]] : [];
        } else {
            $kk = KartuKeluarga::query()
                ->where('no_kk', $keyword)
                ->with(['kepalaKeluarga', 'anggota', 'rumah.rt.rw'])
                ->first();

            if ($kk) {
                $this->detail = 'kk';
                $this->results = [[
                    'id' => $kk->id,
                    'no_kk' => $this->mask($kk->no_kk),
                    'kepala' => optional($kk->kepalaKeluarga)->nama ?? '—',
                    'alamat' => $this->alamatRingkasKk($kk),
                    'jumlah' => $kk->anggota->count(),
                    'anggota' => $kk->anggota->map(fn ($a) => [
                        'nama' => $a->nama,
                        'nik' => $this->mask($a->nik),
                        'jk' => $a->jenis_kelamin,
                        'status' => $a->status_kk ?: ($a->id === $kk->kepala_keluarga_id ? 'Kepala Keluarga' : 'Anggota'),
                    ])->toArray(),
                ]];
            } else {
                $this->results = [];
            }
        }
    }

    private function mask(?string $value): string
    {
        if (!$value) return '—';
        $len = strlen($value);
        if ($len <= 4) return str_repeat('*', $len);
        return str_repeat('*', $len - 4).substr($value, -4);
    }

    private function alamatRingkas(Penduduk $p): string
    {
        $rt = optional(optional(optional($p->kartuKeluarga)->rumah)->rt)->nama;
        $rw = optional(optional(optional(optional($p->kartuKeluarga)->rumah)->rt)->rw)->nama;
        $parts = [];
        if ($rt) $parts[] = 'RT '.$rt;
        if ($rw) $parts[] = 'RW '.$rw;
        return $parts ? implode(' / ', $parts) : 'Desa Waeleman';
    }

    private function alamatRingkasKk(KartuKeluarga $kk): string
    {
        $rt = optional(optional($kk->rumah)->rt)->nama;
        $rw = optional(optional(optional($kk->rumah)->rt)->rw)->nama;
        $parts = [];
        if ($rt) $parts[] = 'RT '.$rt;
        if ($rw) $parts[] = 'RW '.$rw;
        return $parts ? implode(' / ', $parts) : 'Desa Waeleman';
    }

    public function render()
    {
        return view('livewire.publik.cek-data')->layout('layouts.publik');
    }
}
