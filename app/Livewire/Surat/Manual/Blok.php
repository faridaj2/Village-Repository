<?php

namespace App\Livewire\Surat\Manual;

use App\Models\SuratBlock;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Blok Kode Surat')]
class Blok extends Component
{
    public ?int $blockId = null;

    #[Validate('required|string|max:255')]
    public $nama = '';

    #[Validate('required|string')]
    public $kode = '';

    public function mount()
    {
    }

    public function edit($id)
    {
        $block = SuratBlock::findOrFail($id);
        $this->blockId = $block->id;
        $this->nama = $block->nama;
        $this->kode = $block->kode;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nama' => $this->nama,
            'kode' => $this->kode,
        ];

        if ($this->blockId) {
            SuratBlock::findOrFail($this->blockId)->update($data);
            session()->flash('message', 'Blok berhasil diupdate.');
        } else {
            SuratBlock::create($data);
            session()->flash('message', 'Blok berhasil dibuat.');
        }

        $this->reset(['nama', 'kode', 'blockId']);
    }

    public function delete($id)
    {
        SuratBlock::findOrFail($id)->delete();
        session()->flash('message', 'Blok dihapus.');
    }

    public function render()
    {
        return view('livewire.surat.manual.blok', [
            'blocks' => SuratBlock::orderBy('nama')->get(),
        ]);
    }
}
