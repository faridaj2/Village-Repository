<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Pengaturan Sistem</h2>
            <p class="text-sm text-gray-500">Kelola pengaturan aplikasi & landing page</p>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Pengaturan Umum -->
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Pengaturan Umum</h3>
                <p class="text-xs text-gray-500">Nama desa & teks sambutan</p>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Desa</label>
                <input wire:model="namaDesa" type="text" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Teks Sambutan</label>
                <input wire:model="sambutan" type="text" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>
            <div>
                <label class="flex items-center justify-between p-4 bg-gray-50 rounded-xl cursor-pointer">
                    <div>
                        <span class="text-sm font-medium text-gray-900">Registrasi Publik</span>
                        <p class="text-xs text-gray-500">Izinkan warga mendaftar akun sendiri</p>
                    </div>
                    <div class="relative">
                        <input type="checkbox" wire:model.live="enableRegistration" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-indigo-500 transition-colors"></div>
                        <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                    </div>
                </label>
            </div>
            <div class="flex justify-end">
                <button wire:click="saveGeneral" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Hero Slider -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Hero Slider</h3>
                    <p class="text-xs text-gray-500">Foto & teks di landing page</p>
                </div>
            </div>
            <button wire:click="addSlider" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Slide
            </button>
        </div>

        <div class="space-y-4">
            @foreach ($sliders as $index => $slider)
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Slide {{ $index + 1 }}</span>
                        <button wire:click="removeSlider({{ $index }})" wire:confirm="Hapus slide ini?" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">URL Gambar</label>
                            <input wire:model="sliders.{{ $index }}.img" type="text" placeholder="https://..." class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Judul</label>
                            <input wire:model="sliders.{{ $index }}.title" type="text" placeholder="Judul slide" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Subtitle</label>
                            <input wire:model="sliders.{{ $index }}.sub" type="text" placeholder="Subtitle slide" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>
                    @if ($slider['img'])
                        <div class="mt-3 h-24 rounded-lg overflow-hidden bg-gray-200">
                            <img src="{{ $slider['img'] }}" alt="Preview" class="w-full h-full object-cover" onerror="this.style.display='none'">
                        </div>
                    @endif
                </div>
            @endforeach

            @if (count($sliders) === 0)
                <div class="text-center py-8">
                    <p class="text-sm text-gray-500">Belum ada slide. Klik "Tambah Slide" untuk menambahkan.</p>
                </div>
            @endif
        </div>

        <div class="flex justify-end mt-5 pt-5 border-t border-gray-100">
            <button wire:click="saveSliders" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Simpan Slider</button>
        </div>
    </div>
</div>
