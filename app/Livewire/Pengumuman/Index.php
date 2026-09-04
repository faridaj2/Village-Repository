<?php

namespace App\Livewire\Pengumuman;

use App\Models\Pengumuman;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Pengumuman')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    // Form
    public $editId = null;
    public $judul = '';
    public $isi = '';
    public $kategori = '';
    public $tanggal_publish = '';
    public $is_published = false;
    public $showForm = false;

    protected $rules = [
        'judul' => 'required|string|max:255',
        'isi' => 'required|string',
        'kategori' => 'nullable|string|max:100',
        'tanggal_publish' => 'required|date',
        'is_published' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openForm()
    {
        $this->resetForm();
        $this->tanggal_publish = now()->format('Y-m-d');
        $this->showForm = true;
    }

    public function edit($id)
    {
        $p = Pengumuman::findOrFail($id);
        $this->editId = $p->id;
        $this->judul = $p->judul;
        $this->isi = $p->isi;
        $this->kategori = $p->kategori ?? '';
        $this->tanggal_publish = $p->tanggal_publish->format('Y-m-d');
        $this->is_published = $p->is_published;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'judul' => $this->judul,
            'isi' => $this->isi,
            'kategori' => $this->kategori ?: null,
            'tanggal_publish' => $this->tanggal_publish,
            'is_published' => $this->is_published,
        ];

        if ($this->editId) {
            Pengumuman::findOrFail($this->editId)->update($data);
            session()->flash('message', 'Pengumuman berhasil diperbarui.');
        } else {
            Pengumuman::create($data);
            session()->flash('message', 'Pengumuman berhasil ditambahkan.');
        }

        $this->resetForm();
    }

    public function togglePublish($id)
    {
        $p = Pengumuman::findOrFail($id);
        $p->update(['is_published' => !$p->is_published]);
        session()->flash('message', 'Status pengumuman berhasil diperbarui.');
    }

    public function delete($id)
    {
        Pengumuman::findOrFail($id)->delete();
        session()->flash('message', 'Pengumuman berhasil dihapus.');
    }

    public function cancelForm()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editId = null;
        $this->judul = '';
        $this->isi = '';
        $this->kategori = '';
        $this->tanggal_publish = '';
        $this->is_published = false;
        $this->showForm = false;
    }

    public function render()
    {
        $pengumumans = Pengumuman::query()
            ->when($this->search, fn ($q) => $q->where('judul', 'like', '%' . $this->search . '%'))
            ->latest('tanggal_publish')
            ->paginate(10);

        return view('livewire.pengumuman.index', [
            'pengumumans' => $pengumumans,
        ]);
    }
}
