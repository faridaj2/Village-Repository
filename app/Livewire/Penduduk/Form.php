<?php

namespace App\Livewire\Penduduk;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Rt;
use App\Models\Rumah;
use App\Models\Rw;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Form Penduduk')]
class Form extends Component
{
    public ?Penduduk $penduduk = null;

    #[Validate('required|string|max:16')]
    public $nik = '';

    #[Validate('required|string|max:255')]
    public $nama = '';

    #[Validate('nullable|string|max:255')]
    public $tempat_lahir = '';

    #[Validate('nullable|date')]
    public $tanggal_lahir = '';

    #[Validate('required|in:L,P')]
    public $jenis_kelamin = 'L';

    #[Validate('nullable|string|max:50')]
    public $agama = '';

    #[Validate('nullable|string|max:100')]
    public $pendidikan_terakhir = '';

    #[Validate('nullable|string|max:100')]
    public $pekerjaan = '';

    #[Validate('nullable|string|max:50')]
    public $status_kawin = '';

    #[Validate('required|in:aktif,pindah,meninggal')]
    public $status = 'aktif';

    #[Validate('required|in:penduduk,pendatang')]
    public $jenis_penduduk = 'penduduk';

    #[Validate('nullable|in:suami,istri,anak')]
    public $status_kk = '';

    // Status khusus
    public $miskin = false;

    // KK fields
    public $isKepalaKeluarga = false;
    public $no_kk_baru = '';
    public $kkSearch = '';
    public $selectedKkId = '';
    public $kkResults = [];
    public $showKkDropdown = false;

    // RT/RW fields
    public $rwList = [];
    public $rtListForm = [];
    public $selectedRwForm = '';
    public $selectedRtForm = '';

    // Rumah KK fields
    public $hasRumah = false;
    public $rumahRwId = '';
    public $rumahRtId = '';
    public $rumahRtList = [];
    public $kodeRumah = '';
    public $kategoriRumah = '';
    public $kategoriRtlh = '';
    public $teraliriListrik = false;
    public $punyaMckRumah = false;
    public $posisi = '';

    // Rumah Individu fields
    public $hasRumahIndividu = false;
    public $rumahIndividuRwId = '';
    public $rumahIndividuRtId = '';
    public $rumahIndividuRtList = [];
    public $kodeRumahIndividu = '';
    public $kategoriRumahIndividu = '';
    public $kategoriRtlhIndividu = '';
    public $teraliriListrikIndividu = false;
    public $punyaMckIndividu = false;
    public $posisiIndividu = '';

    public function mount($penduduk = null)
    {
        $this->penduduk = $penduduk instanceof Penduduk ? $penduduk : ($penduduk ? Penduduk::find($penduduk) : null);
        $this->rwList = Rw::orderBy('nama')->get();

        if ($this->penduduk) {
            $this->fill($this->penduduk->only([
                'nik', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'agama', 'pendidikan_terakhir', 'pekerjaan', 'status_kawin', 'status', 'jenis_penduduk', 'status_kk',
            ]));
            $this->tanggal_lahir = $this->penduduk->tanggal_lahir?->format('Y-m-d');

            // Load status khusus
            $statusKhusus = $this->penduduk->status_khusus ?? [];
            $this->miskin = in_array('miskin', $statusKhusus);

            if ($this->penduduk->kartu_keluarga_id) {
                $this->selectedKkId = $this->penduduk->kartu_keluarga_id;
                $kk = $this->penduduk->kartuKeluarga;
                if ($kk) {
                    $this->kkSearch = $kk->no_kk;
                    if ($kk->kepala_keluarga_id === $this->penduduk->id) {
                        $this->isKepalaKeluarga = true;
                        $this->no_kk_baru = $kk->no_kk;
                    }
                    // Load RT/RW langsung dari Kartu Keluarga
                    if ($kk->rt) {
                        $this->selectedRwForm = $kk->rt->rw_id;
                        $this->rtListForm = Rt::where('rw_id', $this->selectedRwForm)->orderBy('nama')->get();
                        $this->selectedRtForm = $kk->rt_id;
                    }
                }
            }

            // Load rumah dari KK jika kepala keluarga
            if ($this->isKepalaKeluarga && $this->penduduk->kartuKeluarga?->rumah) {
                $rumah = $this->penduduk->kartuKeluarga->rumah;
                
                // Hanya aktifkan toggle jika ada DETAIL rumah yang diisi (bukan cuma rt_id)
                if ($rumah->kode_rumah || $rumah->kategori_rumah || $rumah->teraliri_listrik || $rumah->punya_mck) {
                    $this->hasRumah = true;
                    if ($rumah->rt) {
                        $this->rumahRwId = $rumah->rt->rw_id;
                        $this->rumahRtList = Rt::where('rw_id', $this->rumahRwId)->orderBy('nama')->get();
                        $this->rumahRtId = $rumah->rt_id;
                    }
                    $this->kodeRumah = $rumah->kode_rumah ?? '';
                    $this->kategoriRumah = $rumah->kategori_rumah ?? '';
                    $this->kategoriRtlh = $rumah->kategori_rtlh ?? '';
                    $this->teraliriListrik = $rumah->teraliri_listrik ?? false;
                    $this->punyaMckRumah = $rumah->punya_mck ?? false;
                    $this->posisi = $rumah->posisi ?? '';
                } else {
                    $this->hasRumah = false; // Hanya ada RT/RW, biarkan toggle mati
                }
            }

            // Load rumah individu
            if ($this->penduduk->rumah_id && $this->penduduk->rumah) {
                $rumah = $this->penduduk->rumah;
                $this->hasRumahIndividu = true;
                if ($rumah->rt) {
                    $this->rumahIndividuRwId = $rumah->rt->rw_id;
                    $this->rumahIndividuRtList = Rt::where('rw_id', $this->rumahIndividuRwId)->orderBy('nama')->get();
                    $this->rumahIndividuRtId = $rumah->rt_id;
                }
                $this->kodeRumahIndividu = $rumah->kode_rumah ?? '';
                $this->kategoriRumahIndividu = $rumah->kategori_rumah ?? '';
                $this->kategoriRtlhIndividu = $rumah->kategori_rtlh ?? '';
                $this->teraliriListrikIndividu = $rumah->teraliri_listrik ?? false;
                $this->punyaMckIndividu = $rumah->punya_mck ?? false;
                $this->posisiIndividu = $rumah->posisi ?? '';
            }
        }
    }

    public function updatedSelectedRwForm()
    {
        $this->selectedRtForm = '';
        if ($this->selectedRwForm) {
            $this->rtListForm = Rt::where('rw_id', $this->selectedRwForm)->orderBy('nama')->get();
        } else {
            $this->rtListForm = [];
        }
        
        // Sinkronkan dengan Rumah KK jika Kepala Keluarga
        if ($this->isKepalaKeluarga && $this->hasRumah) {
            $this->rumahRwId = $this->selectedRwForm;
            $this->updatedRumahRwId();
        }
    }

    public function updatedSelectedRtForm()
    {
        // Sinkronkan dengan Rumah KK jika Kepala Keluarga
        if ($this->isKepalaKeluarga && $this->hasRumah) {
            $this->rumahRtId = $this->selectedRtForm;
        }
    }

    public function updatedRumahRwId()
    {
        $this->rumahRtId = '';
        if ($this->rumahRwId) {
            $this->rumahRtList = Rt::where('rw_id', $this->rumahRwId)->orderBy('nama')->get();
        } else {
            $this->rumahRtList = [];
        }
        
        // Sinkronkan dengan Form KK jika Kepala Keluarga
        if ($this->isKepalaKeluarga) {
            $this->selectedRwForm = $this->rumahRwId;
            $this->updatedSelectedRwForm();
        }
    }

    public function updatedRumahRtId()
    {
        // Sinkronkan dengan Form KK jika Kepala Keluarga
        if ($this->isKepalaKeluarga) {
            $this->selectedRtForm = $this->rumahRtId;
        }
    }

    public function updatedRumahIndividuRwId()
    {
        $this->rumahIndividuRtId = '';
        if ($this->rumahIndividuRwId) {
            $this->rumahIndividuRtList = Rt::where('rw_id', $this->rumahIndividuRwId)->orderBy('nama')->get();
        } else {
            $this->rumahIndividuRtList = [];
        }
    }

    public function updatedKkSearch()
    {
        if (strlen($this->kkSearch) >= 3) {
            $this->kkResults = KartuKeluarga::where('no_kk', 'like', '%' . $this->kkSearch . '%')
                ->limit(10)
                ->get();
            $this->showKkDropdown = true;
        } else {
            $this->kkResults = [];
            $this->showKkDropdown = false;
        }
    }

    public function selectKk($id, $noKk)
    {
        $this->selectedKkId = $id;
        $this->kkSearch = $noKk;
        $this->showKkDropdown = false;
        $this->kkResults = [];

        // PERBAIKAN: Load RT/RW langsung dari Kartu Keluarga, BUKAN dari rumah
        $kk = KartuKeluarga::find($id);
        if ($kk && $kk->rt_id) {
            $this->selectedRwForm = $kk->rt->rw_id ?? '';
            $this->rtListForm = Rt::where('rw_id', $this->selectedRwForm)->orderBy('nama')->get();
            $this->selectedRtForm = $kk->rt_id;
        } else {
            $this->selectedRwForm = '';
            $this->selectedRtForm = '';
            $this->rtListForm = [];
        }
    }

    public function updatedIsKepalaKeluarga($value)
    {
        if ($value) {
            // Jika diaktifkan: bersihkan pilihan KK yang sudah ada
            $this->selectedKkId = '';
            $this->kkSearch = '';
            $this->kkResults = [];
            $this->showKkDropdown = false;
            
            // Prefill data jika sedang edit dan sudah punya KK
            if ($this->penduduk && $this->penduduk->kartuKeluarga) {
                $this->no_kk_baru = $this->penduduk->kartuKeluarga->no_kk;
                if ($this->penduduk->kartuKeluarga->rt) {
                    $this->selectedRwForm = $this->penduduk->kartuKeluarga->rt->rw_id;
                    $this->rtListForm = Rt::where('rw_id', $this->selectedRwForm)->orderBy('nama')->get();
                    $this->selectedRtForm = $this->penduduk->kartuKeluarga->rt_id;
                }
            }
        } else {
            // Jika dimatikan: bersihkan data KK baru agar form "Cari KK" muncul bersih
            $this->no_kk_baru = '';
            $this->selectedRwForm = '';
            $this->selectedRtForm = '';
            $this->rtListForm = [];
        }
    }

    public function updatedHasRumah($value)
    {
        // Method ini eksplisit didefinisikan untuk memastikan tidak ada state yang terpengaruh
        // saat toggle "Punya Rumah KK" diklik. Tidak mengubah isKepalaKeluarga.
        if ($value) {
            // Jika diaktifkan dan Kepala Keluarga, sinkronkan RT/RW dari form KK
            if ($this->isKepalaKeluarga && $this->selectedRwForm) {
                $this->rumahRwId = $this->selectedRwForm;
                $this->rumahRtId = $this->selectedRtForm;
                $this->rumahRtList = Rt::where('rw_id', $this->rumahRwId)->orderBy('nama')->get();
            }
        }
    }

    public function clearKk()
    {
        $this->selectedKkId = '';
        $this->kkSearch = '';
        $this->showKkDropdown = false;
        $this->kkResults = [];
    }

    public function save()
    {
        $rules = [
            'nik' => 'required|string|max:16',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:50',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'status_kawin' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,pindah,meninggal',
            'jenis_penduduk' => 'required|in:penduduk,pendatang',
        ];

        if ($this->isKepalaKeluarga) {
            $rules['no_kk_baru'] = 'required|string|max:16|min:16';
            $rules['selectedRtForm'] = 'required|exists:rts,id';
        }

        // RT/RW rumah KK sudah divalidasi di selectedRtForm saat isKepalaKeluarga

        if ($this->hasRumahIndividu) {
            $rules['rumahIndividuRtId'] = 'required|exists:rts,id';
        }

        $this->validate($rules);

        if (!$this->penduduk) {
            $this->authorize('create', Penduduk::class);
        } else {
            $this->authorize('update', $this->penduduk);
        }

        $kkId = null;

        if ($this->isKepalaKeluarga) {
            if ($this->penduduk && $this->penduduk->kartu_keluarga_id) {
                // Edit: sudah punya KK, gunakan yang ada
                $kkId = $this->penduduk->kartu_keluarga_id;
                $kk = KartuKeluarga::find($kkId);
                
                if ($kk && $this->no_kk_baru && $kk->no_kk !== $this->no_kk_baru) {
                    $kk->update(['no_kk' => $this->no_kk_baru]);
                }
                
                // Update RT/RW langsung di Kartu Keluarga
                if ($kk && $this->selectedRtForm) {
                    $kk->update(['rt_id' => $this->selectedRtForm]);
                }
            } else {
                // Create: buat KK baru
                $kk = KartuKeluarga::create([
                    'no_kk' => $this->no_kk_baru,
                    'rt_id' => $this->selectedRtForm,
                    'alamat' => null,
                ]);
                $kkId = $kk->id;
            }
        } else {
            $kkId = $this->selectedKkId ?: null;
        }

        $data = [
            'nik' => $this->nik,
            'nama' => $this->nama,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir ?: null,
            'jenis_kelamin' => $this->jenis_kelamin,
            'agama' => $this->agama,
            'pendidikan_terakhir' => $this->pendidikan_terakhir,
            'pekerjaan' => $this->pekerjaan,
            'status_kawin' => $this->status_kawin,
            'kartu_keluarga_id' => $kkId,
            'status' => $this->status,
            'jenis_penduduk' => $this->jenis_penduduk,
            'status_khusus' => $this->miskin ? ['miskin'] : null,
            'status_kk' => $this->status_kk ?: null,
        ];

        // Handle rumah KK
        if ($this->hasRumah && $this->isKepalaKeluarga) {
            $rumahData = [
                'kode_rumah' => $this->kodeRumah ?: null,
                'kategori_rumah' => $this->kategoriRumah ?: null,
                'kategori_rtlh' => $this->kategoriRtlh ?: null,
                'teraliri_listrik' => $this->teraliriListrik,
                'punya_mck' => $this->punyaMckRumah,
                'posisi' => $this->posisi ?: null,
            ];

            $existingRumahId = $this->penduduk?->kartuKeluarga?->rumah_id;
            if ($existingRumahId) {
                Rumah::where('id', $existingRumahId)->update($rumahData);
            } else {
                $rumahBaru = Rumah::create($rumahData);
                if ($kkId) {
                    KartuKeluarga::where('id', $kkId)->update(['rumah_id' => $rumahBaru->id]);
                }
            }
        } elseif (!$this->hasRumah && $this->isKepalaKeluarga && $this->penduduk?->kartuKeluarga?->rumah_id) {
            // Hapus relasi rumah jika di-uncheck, agar tidak muncul di detail, tapi rt_id tetap ada di KK
            KartuKeluarga::where('id', $this->penduduk->kartu_keluarga_id)->update(['rumah_id' => null]);
        }

        // Handle rumah individu
        if ($this->hasRumahIndividu) {
            $rumahIndividuData = [
                'kode_rumah' => $this->kodeRumahIndividu ?: null,
                'rt_id' => $this->rumahIndividuRtId,
                'kategori_rumah' => $this->kategoriRumahIndividu ?: null,
                'kategori_rtlh' => $this->kategoriRtlhIndividu ?: null,
                'teraliri_listrik' => $this->teraliriListrikIndividu,
                'punya_mck' => $this->punyaMckIndividu,
                'posisi' => $this->posisiIndividu ?: null,
            ];

            if ($this->penduduk?->rumah_id) {
                Rumah::where('id', $this->penduduk->rumah_id)->update($rumahIndividuData);
            } else {
                $rumahBaru = Rumah::create($rumahIndividuData);
                $data['rumah_id'] = $rumahBaru->id;
            }
        } elseif (!$this->hasRumahIndividu && $this->penduduk?->rumah_id) {
            $data['rumah_id'] = null;
        }

        if ($this->penduduk) {
            $this->penduduk->update($data);
            $this->penduduk->refresh();

            if ($this->isKepalaKeluarga && $kkId) {
                KartuKeluarga::where('id', $kkId)->update(['kepala_keluarga_id' => $this->penduduk->id]);
            }

            session()->flash('message', 'Data penduduk berhasil diperbarui.');
            return redirect()->route('penduduk.show', $this->penduduk);
        } else {
            $penduduk = Penduduk::create($data);

            if ($this->isKepalaKeluarga && $kkId) {
                KartuKeluarga::where('id', $kkId)->update(['kepala_keluarga_id' => $penduduk->id]);
            }

            session()->flash('message', 'Data penduduk berhasil ditambahkan.');
            return redirect()->route('penduduk.show', $penduduk);
        }
    }

    public function render()
    {
        return view('livewire.penduduk.form');
    }
}
