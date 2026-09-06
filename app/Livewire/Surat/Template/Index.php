<?php

namespace App\Livewire\Surat\Template;

use App\Models\SuratTemplate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Template Surat')]
class Index extends Component
{
    public function toggleActive($id)
    {
        $template = SuratTemplate::findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);
        session()->flash('message', "Template {$template->nama} " . ($template->is_active ? 'diaktifkan' : 'dinonaktifkan') . '.');
    }

    public function delete($id)
    {
        $template = SuratTemplate::findOrFail($id);
        if ($template->surats()->count() > 0) {
            session()->flash('error', 'Template tidak bisa dihapus karena masih digunakan.');
            return;
        }
        $template->delete();
        session()->flash('message', 'Template berhasil dihapus.');
    }

    public function clone($id)
    {
        $original = SuratTemplate::findOrFail($id);
        $clone = $original->replicate();
        $clone->nama = $original->nama . ' (Copy)';
        $clone->slug = $original->slug . '-copy-' . uniqid();
        $clone->is_active = false;
        $clone->created_by = auth()->id();
        $clone->save();

        session()->flash('message', 'Template berhasil diduplikat.');
        return redirect()->route('surat.template.edit', $clone->id);
    }

    public function render()
    {
        return view('livewire.surat.template.index', [
            'templates' => SuratTemplate::with('creator')->latest()->get(),
        ]);
    }
}
