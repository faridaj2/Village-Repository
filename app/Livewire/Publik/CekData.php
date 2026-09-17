<?php

namespace App\Livewire\Publik;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\WithPagination;

class CekData extends Component
{
    use WithPagination;

    public string $tipe = 'nama';
    public string $keyword = '';
    public bool $searched = false;
    public ?string $detail = null;

    protected $queryString = ['tipe', 'keyword'];

    public function updatedTipe(): void
    {
        $this->reset(['keyword', 'searched', 'detail']);
        $this->resetPage();
    }

    public function updatedKeyword(): void
    {
        if ($this->tipe !== 'nama') {
            $this->keyword = preg_replace('/\D/', '', $this->keyword);
        }
        if (strlen($this->keyword) > 16 && $this->tipe !== 'nama') {
            $this->keyword = substr($this->keyword, 0, 16);
        }
    }

    public function search(): void
    {
        $key = 'cek-data:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $this->addError('keyword', 'Terlalu banyak permintaan. Coba lagi dalam ' . RateLimiter::availableIn($key) . ' detik.');
            return;
        }
        RateLimiter::hit($key, 60);

        $rules = [
            'tipe' => 'required|in:nama,nik,kk',
            'keyword' => 'required|string|min:3|max:100',
        ];

        if ($this->tipe === 'nik' || $this->tipe === 'kk') {
            $rules['keyword'] = 'required|digits:16';
        }

        $this->validate($rules, [
            'keyword.required' => 'Masukkan kata kunci pencarian.',
            'keyword.min' => 'Minimal 3 karakter.',
            'keyword.digits' => 'Harus tepat 16 digit angka.',
        ]);

        $this->searched = true;
        $this->detail = null;
        $this->resetPage();
    }

    public function resetSearch(): void
    {
        $this->reset(['keyword', 'searched', 'detail']);
        $this->resetPage();
    }

    private function mask(?string $value): string
    {
        if (!$value) return '—';
        $len = strlen($value);
        if ($len <= 4) return str_repeat('*', $len);
        return str_repeat('*', $len - 4) . substr($value, -4);
    }

    private function alamatRingkas(Penduduk $p): string
    {
        $rt = optional(optional(optional($p->kartuKeluarga)->rumah)->rt)->nama;
        $rw = optional(optional(optional(optional($p->kartuKeluarga)->rumah)->rt)->rw)->nama;
        $parts = [];
        if ($rt) $parts[] = 'RT ' . $rt;
        if ($rw) $parts[] = 'RW ' . $rw;
        return $parts ? implode(' / ', $parts) : 'Desa Waeleman';
    }

    private function alamatRingkasKk(KartuKeluarga $kk): string
    {
        $rt = optional(optional($kk->rumah)->rt)->nama;
        $rw = optional(optional(optional($kk->rumah)->rt)->rw)->nama;
        $parts = [];
        if ($rt) $parts[] = 'RT ' . $rt;
        if ($rw) $parts[] = 'RW ' . $rw;
        return $parts ? implode(' / ', $parts) : 'Desa Waeleman';
    }

    public function render()
    {
        $results = [];
        $paginator = null;

        if ($this->searched && $this->keyword) {
            $keyword = trim($this->keyword);

            if ($this->tipe === 'nama') {
                $paginator = Penduduk::query()
                    ->where('status', 'aktif')
                    ->where('nama', 'like', '%' . $keyword . '%')
                    ->with('kartuKeluarga.rumah.rt.rw')
                    ->orderBy('nama')
                    ->paginate(10);

                $results = collect($paginator->items())->map(fn($p) => [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'nik' => $this->mask($p->nik),
                    'kk' => $p->kartuKeluarga ? $this->mask($p->kartuKeluarga->no_kk) : '—',
                    'jk' => $p->jenis_kelamin,
                    'umur' => $p->tanggal_lahir ? $p->tanggal_lahir->age . ' th' : '—',
                    'alamat' => $this->alamatRingkas($p),
                ])->toArray();

            } elseif ($this->tipe === 'nik') {
                $p = Penduduk::query()
                    ->where('nik', $keyword)
                    ->with('kartuKeluarga.rumah.rt.rw')
                    ->first();

                $results = $p ? [[
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'nik' => $this->mask($p->nik),
                    'kk' => $p->kartuKeluarga ? $this->mask($p->kartuKeluarga->no_kk) : '—',
                    'jk' => $p->jenis_kelamin,
                    'umur' => $p->tanggal_lahir ? $p->tanggal_lahir->age . ' th' : '—',
                    'alamat' => $this->alamatRingkas($p),
                ]] : [];

            } else {
                $kk = KartuKeluarga::query()
                    ->where('no_kk', $keyword)
                    ->with(['kepalaKeluarga', 'anggota', 'rumah.rt.rw'])
                    ->first();

                if ($kk) {
                    $this->detail = 'kk';
                    $anggota = $kk->anggota->sortBy(fn($a) => $a->id === $kk->kepala_keluarga_id ? 0 : 1);
                    $results = [[
                        'id' => $kk->id,
                        'no_kk' => $this->mask($kk->no_kk),
                        'kepala' => optional($kk->kepalaKeluarga)->nama ?? '—',
                        'alamat' => $this->alamatRingkasKk($kk),
                        'jumlah' => $kk->anggota->count(),
                        'anggota' => $anggota->map(fn($a) => [
                            'nama' => $a->nama,
                            'nik' => $this->mask($a->nik),
                            'jk' => $a->jenis_kelamin,
                            'umur' => $a->tanggal_lahir ? $a->tanggal_lahir->age . ' th' : '—',
                            'status' => $a->status_kk ?: ($a->id === $kk->kepala_keluarga_id ? 'Kepala Keluarga' : 'Anggota'),
                        ])->values()->toArray(),
                    ]];
                }
            }
        }

        return view('livewire.publik.cek-data', [
            'results' => $results,
            'paginator' => $paginator,
        ])->layout('layouts.publik');
    }
}
