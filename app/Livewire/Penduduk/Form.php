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

    // Koordinat (opsional)
    public $posisi = '';

    // Rumah fields (opsional, langsung ke penduduk)
    public $hasRumah = false;
    public $rumahRwId = '';
    public $rumahRtId = '';
    public $rumahRtList = [];
    public $kategoriRumah = '';
    public $teraliriListrik = false;
    public $punyaMckRumah = false;

    public function mount($penduduk = null)
    {
        $this->penduduk = $penduduk ? Penduduk::find($penduduk) : null;
        $this->rwList = Rw::orderBy('nama')->get();

        if ($this->penduduk) {
            $this->fill($this->penduduk->only([
                'nik', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'agama', 'pendidikan_terakhir', 'pekerjaan', 'status_kawin', 'status', 'jenis_penduduk',
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
                    }
                    // Load RT/RW from KK's house
                    if ($kk->rumah && $kk->rumah->rt) {
                        $this->selectedRwForm = $kk->rumah->rt->rw_id;
                        $this->rtListForm = Rt::where('rw_id', $this->selectedRwForm)->orderBy('nama')->get();
                        $this->selectedRtForm = $kk->rumah->rt_id;
                    }
                }
            }

            // Load existing rumah (langsung)
            if ($this->penduduk->rumah_id) {
                $this->hasRumah = true;
                $rumah = $this->penduduk->rumah;
                if ($rumah && $rumah->rt) {
                    $this->rumahRwId = $rumah->rt->rw_id;
                    $this->rumahRtList = Rt::where('rw_id', $this->rumahRwId)->orderBy('nama')->get();
                    $this->rumahRtId = $rumah->rt_id;
                }
                $this->kategoriRumah = $rumah->kategori_rumah ?? '';
                $this->teraliriListrik = $rumah->teraliri_listrik ?? false;
                $this->punyaMckRumah = $rumah->punya_mck ?? false;
                $this->posisi = $rumah->posisi ?? '';
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
    }

    public function updatedRumahRwId()
    {
        $this->rumahRtId = '';
        if ($this->rumahRwId) {
            $this->rumahRtList = Rt::where('rw_id', $this->rumahRwId)->orderBy('nama')->get();
        } else {
            $this->rumahRtList = [];
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

        // Load RT/RW from selected KK's house
        $kk = KartuKeluarga::find($id);
        if ($kk && $kk->rumah && $kk->rumah->rt) {
            $this->selectedRwForm = $kk->rumah->rt->rw_id;
            $this->rtListForm = Rt::where('rw_id', $this->selectedRwForm)->orderBy('nama')->get();
            $this->selectedRtForm = $kk->rumah->rt_id;
        } else {
            $this->selectedRwForm = '';
            $this->selectedRtForm = '';
            $this->rtListForm = [];
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

        if ($this->hasRumah) {
            $rules['rumahRtId'] = 'required|exists:rts,id';
        }

        $this->validate($rules);

        if (!$this->penduduk) {
            $this->authorize('create', Penduduk::class);
        } else {
            $this->authorize('update', $this->penduduk);
        }

        $kkId = null;

        // If creating as head of family, create Rumah + KK
        if ($this->isKepalaKeluarga) {
            $rt = Rt::find($this->selectedRtForm);

            // Create a house linked to the selected RT
            $rumah = Rumah::create([
                'rt_id' => $this->selectedRtForm,
                'alamat' => null,
                'posisi' => $this->posisi ?: null,
            ]);

            $kk = KartuKeluarga::create([
                'no_kk' => $this->no_kk_baru,
                'rumah_id' => $rumah->id,
                'alamat' => null,
            ]);
            $kkId = $kk->id;
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
        ];

        // Handle rumah langsung ke penduduk
        if ($this->hasRumah) {
            $rumahData = [
                'rt_id' => $this->rumahRtId,
                'kategori_rumah' => $this->kategoriRumah ?: null,
                'teraliri_listrik' => $this->teraliriListrik,
                'punya_mck' => $this->punyaMckRumah,
                'posisi' => $this->posisi ?: null,
            ];

            if ($this->penduduk && $this->penduduk->rumah_id) {
                // Update existing rumah
                Rumah::where('id', $this->penduduk->rumah_id)->update($rumahData);
                $data['rumah_id'] = $this->penduduk->rumah_id;
            } else {
                // Create new rumah
                $rumah = Rumah::create($rumahData);
                $data['rumah_id'] = $rumah->id;
            }
        } else {
            $data['rumah_id'] = null;
        }

        if ($this->penduduk) {
            $this->penduduk->update($data);

            if ($this->isKepalaKeluarga && $kkId) {
                KartuKeluarga::where('id', $kkId)->update(['kepala_keluarga_id' => $this->penduduk->id]);
            }

            session()->flash('message', 'Data penduduk berhasil diperbarui.');
        } else {
            $penduduk = Penduduk::create($data);

            if ($this->isKepalaKeluarga && $kkId) {
                KartuKeluarga::where('id', $kkId)->update(['kepala_keluarga_id' => $penduduk->id]);
            }

            session()->flash('message', 'Data penduduk berhasil ditambahkan.');
        }

        return redirect()->route('penduduk.index');
    }

    public function render()
    {
        return view('livewire.penduduk.form');
    }
}
