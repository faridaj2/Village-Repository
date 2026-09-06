<?php

namespace App\Livewire\Rumah;

use App\Models\Rt;
use App\Models\Rumah;
use App\Models\Rw;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Form Rumah')]
class Form extends Component
{
    public ?Rumah $rumah = null;

    #[Validate('nullable|string|max:50')]
    public $kode_rumah = '';

    #[Validate('nullable|string|max:255')]
    public $alamat = '';

    #[Validate('nullable|string|max:100')]
    public $posisi = '';

    public $punya_mck = false;

    #[Validate('nullable|in:layak,tidak_layak')]
    public $kategori_rtlh = '';

    #[Validate('nullable|in:permanen,semi_permanen,darurat')]
    public $kategori_rumah = '';

    public $teraliri_listrik = false;

    // RT/RW
    public $rwList = [];
    public $rtListForm = [];
    public $rwId = '';
    public $rtId = '';

    public function mount($rumah = null)
    {
        $this->rwList = Rw::orderBy('nama')->get();
        $this->rumah = $rumah instanceof Rumah ? $rumah : ($rumah ? Rumah::find($rumah) : null);

        if ($this->rumah) {
            $this->fill($this->rumah->only([
                'kode_rumah', 'alamat', 'posisi', 'punya_mck',
                'kategori_rtlh', 'kategori_rumah', 'teraliri_listrik',
            ]));

            if ($this->rumah->rt) {
                $this->rwId = $this->rumah->rt->rw_id;
                $this->rtListForm = Rt::where('rw_id', $this->rwId)->orderBy('nama')->get();
                $this->rtId = $this->rumah->rt_id;
            }
        }
    }

    public function updatedRwId()
    {
        $this->rtId = '';
        if ($this->rwId) {
            $this->rtListForm = Rt::where('rw_id', $this->rwId)->orderBy('nama')->get();
        } else {
            $this->rtListForm = [];
        }
    }

    public function save()
    {
        $rules = [
            'kode_rumah' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:255',
            'posisi' => 'nullable|string|max:100',
            'kategori_rtlh' => 'nullable|in:layak,tidak_layak',
            'kategori_rumah' => 'nullable|in:permanen,semi_permanen,darurat',
        ];

        $this->validate($rules);

        if (!$this->rumah) {
            $this->authorize('create', Rumah::class);
        } else {
            $this->authorize('update', $this->rumah);
        }

        $data = [
            'kode_rumah' => $this->kode_rumah ?: null,
            'rt_id' => $this->rtId ?: null,
            'alamat' => $this->alamat ?: null,
            'posisi' => $this->posisi ?: null,
            'punya_mck' => $this->punya_mck,
            'kategori_rtlh' => $this->kategori_rtlh ?: null,
            'kategori_rumah' => $this->kategori_rumah ?: null,
            'teraliri_listrik' => $this->teraliri_listrik,
        ];

        if ($this->rumah) {
            $this->rumah->update($data);
            session()->flash('message', 'Data rumah berhasil diperbarui.');
        } else {
            Rumah::create($data);
            session()->flash('message', 'Data rumah berhasil ditambahkan.');
        }

        return redirect()->route('rumah.index');
    }

    public function render()
    {
        return view('livewire.rumah.form');
    }
}
