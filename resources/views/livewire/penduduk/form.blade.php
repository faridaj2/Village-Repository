<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('penduduk.index') }}" class="p-2 rounded-xl text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $penduduk ? 'Edit Penduduk' : 'Tambah Penduduk' }}</h2>
                <p class="text-sm text-gray-500">{{ $penduduk ? 'Perbarui data penduduk' : 'Input data penduduk baru' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form wire:submit="save" class="space-y-8">
            <!-- Data Pribadi -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    Data Pribadi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK</label>
                        <input wire:model="nik" type="text" placeholder="16 digit NIK" maxlength="16" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input wire:model="nama" type="text" placeholder="Nama lengkap" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir</label>
                        <input wire:model="tempat_lahir" type="text" placeholder="Tempat lahir" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                        <input wire:model="tanggal_lahir" type="date" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                        <select wire:model="jenis_kelamin" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Agama</label>
                        <select wire:model="agama" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Pilih Agama</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pendidikan</label>
                        <input wire:model="pendidikan_terakhir" type="text" placeholder="Contoh: SMA" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan</label>
                        <input wire:model="pekerjaan" type="text" placeholder="Contoh: Petani" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Kawin</label>
                        <select wire:model="status_kawin" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Pilih Status</option>
                            <option value="belum_kawin">Belum Kawin</option>
                            <option value="kawin">Kawin</option>
                            <option value="cerai_hidup">Cerai Hidup</option>
                            <option value="cerai_mati">Cerai Mati</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select wire:model="status" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="aktif">Aktif</option>
                            <option value="pindah">Pindah</option>
                            <option value="meninggal">Meninggal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Penduduk</label>
                        <select wire:model="jenis_penduduk" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="penduduk">Penduduk</option>
                            <option value="pendatang">Pendatang</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Pendatang = sudah menetap, belum urus pindah KK/KTP</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                            <input type="checkbox" wire:model="miskin" class="w-4 h-4 text-red-600 bg-white border-gray-300 rounded focus:ring-red-500">
                            <div>
                                <span class="text-sm font-medium text-gray-900">Miskin</span>
                                <p class="text-xs text-gray-500">Centang jika penduduk tergolong keluarga miskin</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Kartu Keluarga -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <div class="w-6 h-6 bg-emerald-100 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    Kartu Keluarga
                </h3>

                <!-- Toggle Kepala Keluarga -->
                <div class="mb-5">
                    <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                        <div class="relative">
                            <input type="checkbox" wire:model.live="isKepalaKeluarga" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-indigo-500 transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-900">Kepala Keluarga</span>
                            <p class="text-xs text-gray-500">Aktifkan jika penduduk ini adalah kepala keluarga baru</p>
                        </div>
                    </label>
                </div>

                <!-- Jika Kepala Keluarga: Input No KK Baru + RT/RW -->
                @if ($isKepalaKeluarga)
                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-indigo-800 mb-1.5">No. Kartu Keluarga Baru</label>
                            <input wire:model="no_kk_baru" type="text" placeholder="Masukkan 16 digit No. KK" maxlength="16" class="w-full px-4 py-2.5 bg-white border border-indigo-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            @error('no_kk_baru') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-indigo-800 mb-1.5">RW</label>
                                <select wire:model.live="selectedRwForm" class="w-full px-4 py-2.5 bg-white border border-indigo-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="">Pilih RW</option>
                                    @foreach ($rwList as $rw)
                                        <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-800 mb-1.5">RT</label>
                                <select wire:model="selectedRtForm" class="w-full px-4 py-2.5 bg-white border border-indigo-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all" {{ !$selectedRwForm ? 'disabled' : '' }}>
                                    <option value="">{{ $selectedRwForm ? 'Pilih RT' : 'Pilih RW dulu' }}</option>
                                    @foreach ($rtListForm as $rt)
                                        <option value="{{ $rt->id }}">{{ $rt->nama }}</option>
                                    @endforeach
                                </select>
                                @error('selectedRtForm') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <p class="text-xs text-indigo-600">KK & rumah baru akan dibuat otomatis.</p>
                    </div>
                @else
                    <!-- Jika Bukan KK: Search KK yang ada -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Kartu Keluarga</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input wire:model.live.debounce.300ms="kkSearch" type="text" placeholder="Ketik No. KK untuk mencari..." class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            @if ($selectedKkId)
                                <button wire:click="clearKk" type="button" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-md text-gray-400 hover:text-red-500 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
                        </div>

                        <!-- Dropdown hasil pencarian -->
                        @if ($showKkDropdown && count($kkResults) > 0)
                            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                                @foreach ($kkResults as $kk)
                                    <button type="button" wire:click="selectKk({{ $kk->id }}, '{{ $kk->no_kk }}')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                                        <div>
                                            <p class="text-sm font-mono font-medium text-gray-900">{{ $kk->no_kk }}</p>
                                            <p class="text-xs text-gray-500">{{ $kk->kepalaKeluarga?->nama ?? 'Belum ada kepala keluarga' }}</p>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                @endforeach
                            </div>
                        @elseif ($showKkDropdown && strlen($kkSearch) >= 3)
                            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg p-4">
                                <p class="text-sm text-gray-500 text-center">KK tidak ditemukan</p>
                            </div>
                        @endif

                        @if ($selectedKkId)
                            <div class="mt-3 p-3 bg-emerald-50 rounded-xl">
                                <p class="text-xs text-emerald-700 flex items-center gap-1 mb-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    KK terpilih
                                </p>
                                @if ($selectedRwForm && $selectedRtForm)
                                    <p class="text-xs text-gray-600">
                                        RW: <span class="font-semibold">{{ collect($rwList)->firstWhere('id', $selectedRwForm)?->nama ?? '-' }}</span>
                                        / RT: <span class="font-semibold">{{ collect($rtListForm)->firstWhere('id', $selectedRtForm)?->nama ?? '-' }}</span>
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="text-xs text-gray-400 mt-2">Kosongkan jika penduduk belum punya KK</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Rumah (Opsional, langsung ke penduduk) -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <div class="w-6 h-6 bg-amber-100 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    Rumah
                </h3>

                <!-- Toggle Punya Rumah -->
                <div class="mb-5">
                    <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                        <div class="relative">
                            <input type="checkbox" wire:model.live="hasRumah" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-amber-500 transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-900">Punya Rumah</span>
                            <p class="text-xs text-gray-500">Centang jika penduduk memiliki rumah tinggal</p>
                        </div>
                    </label>
                </div>

                @if ($hasRumah)
                    <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-amber-800 mb-1.5">RW</label>
                                <select wire:model.live="rumahRwId" class="w-full px-4 py-2.5 bg-white border border-amber-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 transition-all">
                                    <option value="">Pilih RW</option>
                                    @foreach ($rwList as $rw)
                                        <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-amber-800 mb-1.5">RT</label>
                                <select wire:model="rumahRtId" class="w-full px-4 py-2.5 bg-white border border-amber-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 transition-all" {{ !$rumahRwId ? 'disabled' : '' }}>
                                    <option value="">{{ $rumahRwId ? 'Pilih RT' : 'Pilih RW dulu' }}</option>
                                    @foreach ($rumahRtList as $rt)
                                        <option value="{{ $rt->id }}">{{ $rt->nama }}</option>
                                    @endforeach
                                </select>
                                @error('rumahRtId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-amber-800 mb-1.5">Kategori Rumah</label>
                            <select wire:model="kategoriRumah" class="w-full px-4 py-2.5 bg-white border border-amber-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 transition-all">
                                <option value="">Pilih Kategori</option>
                                <option value="permanen">Permanen</option>
                                <option value="semi_permanen">Semi Permanen</option>
                                <option value="darurat">Darurat</option>
                            </select>
                        </div>

                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl cursor-pointer hover:bg-amber-100/50 transition-colors border border-amber-200">
                                <input type="checkbox" wire:model="teraliriListrik" class="w-4 h-4 text-amber-600 bg-white border-gray-300 rounded focus:ring-amber-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">Teraliri Listrik</span>
                                    <p class="text-xs text-gray-500">Rumah memiliki aliran listrik</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl cursor-pointer hover:bg-amber-100/50 transition-colors border border-amber-200">
                                <input type="checkbox" wire:model="punyaMckRumah" class="w-4 h-4 text-amber-600 bg-white border-gray-300 rounded focus:ring-amber-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">Punya MCK</span>
                                    <p class="text-xs text-gray-500">Rumah memiliki mandi, cuci, kakus</p>
                                </div>
                            </label>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-amber-800 mb-1.5">Latitude <span class="text-amber-400 font-normal">(opsional)</span></label>
                                <input wire:model="latitude" type="text" placeholder="-3.4103139" class="w-full px-4 py-2.5 bg-white border border-amber-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-amber-800 mb-1.5">Longitude <span class="text-amber-400 font-normal">(opsional)</span></label>
                                <input wire:model="longitude" type="text" placeholder="126.9683868" class="w-full px-4 py-2.5 bg-white border border-amber-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 transition-all">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('penduduk.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>
