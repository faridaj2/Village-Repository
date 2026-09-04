<?php

namespace App\Livewire\Pengumuman;

use App\Models\Pengumuman;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.guest')]
#[Title('Pengumuman Desa')]
class Publik extends Component
{
    use WithPagination;

    public function render()
    {
        $pengumumans = Pengumuman::published()
            ->latest('tanggal_publish')
            ->paginate(10);

        return view('livewire.pengumuman.publik', [
            'pengumumans' => $pengumumans,
        ]);
    }
}
