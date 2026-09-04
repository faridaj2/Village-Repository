<?php

namespace App\Livewire\KartuKeluarga;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Rumah;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Form Kartu Keluarga')]
class Form extends Component
{
    public ?KartuKeluarga $kartuKeluarga = null;

    #[Validate('required|string|max:16')]
    public $no_kk = '';

    #[Validate('nullable|exists:penduduks,id')]
    public $kepala_keluarga_id = '';

    #[Validate('nullable|exists:rumahs,id')]
    public $rumah_id = '';

    #[Validate('nullable|string|max:255')]
    public $alamat = '';

    public $pendudukList = [];
    public $rumahList = [];

    public function mount($kartuKeluarga = null)
    {
        $this->kartuKeluarga = $kartuKeluarga ? KartuKeluarga::find($kartuKeluarga) : null;
        $this->pendudukList = Penduduk::where('status', 'aktif')->orderBy('nama')->get();
        $this->rumahList = Rumah::orderBy('kode_rumah')->get();

        if ($this->kartuKeluarga) {
            $this->fill($this->kartuKeluarga->only([
                'no_kk', 'kepala_keluarga_id', 'rumah_id', 'alamat',
            ]));
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'no_kk' => $this->no_kk,
            'kepala_keluarga_id' => $this->kepala_keluarga_id ?: null,
            'rumah_id' => $this->rumah_id ?: null,
            'alamat' => $this->alamat,
        ];

        if ($this->kartuKeluarga) {
            $this->kartuKeluarga->update($data);
            session()->flash('message', 'Data KK berhasil diperbarui.');
        } else {
            KartuKeluarga::create($data);
            session()->flash('message', 'Data KK berhasil ditambahkan.');
        }

        return redirect()->route('kartu-keluarga.index');
    }

    public function render()
    {
        return view('livewire.kartu-keluarga.form');
    }
}
