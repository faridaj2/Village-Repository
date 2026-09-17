<div>
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-brand-900 via-brand-950 to-brand-900 text-white py-14 sm:py-20 relative overflow-hidden">
        <div class="absolute top-0 -left-32 w-96 h-96 bg-gold-500/15 rounded-full blur-3xl" aria-hidden="true"></div>
        <div class="absolute bottom-0 -right-32 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl" aria-hidden="true"></div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center relative">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/10 backdrop-blur-md border border-white/15 rounded-full mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-500 animate-pulse"></span>
                <span class="text-[10px] font-bold tracking-[0.2em] uppercase text-white/90">Layanan Publik</span>
            </div>
            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl leading-tight mb-4">
                Cek <span class="gradient-text">Data Penduduk</span>
            </h1>
            <p class="text-white/70 text-sm sm:text-base max-w-xl mx-auto leading-relaxed">
                Cari data penduduk dan kartu keluarga Desa Waeleman. NIK & No. KK ditampilkan sebagian (masking) untuk menjaga privasi.
            </p>
        </div>
    </section>

    {{-- Form --}}
    <section class="max-w-3xl mx-auto px-4 sm:px-6 -mt-8 relative z-10">
        <div class="bg-white rounded-3xl shadow-[0_24px_60px_-30px_rgba(6,78,59,0.35)] border border-slate-100 p-6 sm:p-8">
            <div class="grid grid-cols-3 gap-2 p-1 bg-slate-100 rounded-2xl mb-6">
                <button wire:click="$set('tipe', 'nama')" type="button"
                    class="py-2.5 text-sm font-bold rounded-xl transition-all {{ $tipe === 'nama' ? 'bg-brand-900 text-white shadow-lg shadow-brand-900/20' : 'text-slate-600 hover:text-brand-900' }}">
                    Nama
                </button>
                <button wire:click="$set('tipe', 'nik')" type="button"
                    class="py-2.5 text-sm font-bold rounded-xl transition-all {{ $tipe === 'nik' ? 'bg-brand-900 text-white shadow-lg shadow-brand-900/20' : 'text-slate-600 hover:text-brand-900' }}">
                    NIK
                </button>
                <button wire:click="$set('tipe', 'kk')" type="button"
                    class="py-2.5 text-sm font-bold rounded-xl transition-all {{ $tipe === 'kk' ? 'bg-brand-900 text-white shadow-lg shadow-brand-900/20' : 'text-slate-600 hover:text-brand-900' }}">
                    No. KK
                </button>
            </div>

            <form wire:submit="search" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        @if ($tipe === 'nama')
                            Nama Penduduk
                        @elseif ($tipe === 'nik')
                            NIK (16 digit)
                        @else
                            Nomor Kartu Keluarga (16 digit)
                        @endif
                    </label>
                    <input
                        type="text"
                        wire:model="keyword"
                        placeholder="{{ $tipe === 'nama' ? 'Contoh: Budi Santoso' : 'Masukkan 16 digit angka' }}"
                        @if ($tipe !== 'nama') inputmode="numeric" maxlength="16" @endif
                        class="w-full px-4 py-3.5 bg-slate-50 border-0 rounded-2xl text-sm focus:ring-2 focus:ring-brand-700 focus:bg-white transition-all placeholder:text-slate-400">
                    @error('keyword')
                        <p class="text-xs text-rose-600 mt-2">{{ $message }}</p>
                    @enderror
                    @if ($tipe === 'nama')
                        <p class="text-xs text-slate-400 mt-2">Pencarian sebagian diperbolehkan, minimal 3 karakter.</p>
                    @else
                        <p class="text-xs text-slate-400 mt-2">Harus sama persis (16 digit).</p>
                    @endif
                </div>
                <button type="submit" wire:loading.attr="disabled"
                    class="btn-gold w-full py-3.5 text-sm font-bold rounded-2xl flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="search">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari Sekarang
                    </span>
                    <span wire:loading wire:target="search" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Mencari...
                    </span>
                </button>
            </form>
        </div>
    </section>

    {{-- Hasil --}}
    @if ($searched)
        <section class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
            @if (count($results) > 0)
                @if ($detail === 'kk')
                    @php $kk = $results[0]; @endphp
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-br from-brand-50 to-white">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-brand-900 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[10px] font-bold text-gold-600 uppercase tracking-widest mb-1">Kartu Keluarga</div>
                                    <div class="font-display text-xl sm:text-2xl text-slate-900 mb-2">{{ $kk['kepala'] }}</div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600">
                                        <div><span class="font-bold text-slate-500">No. KK:</span> <span class="font-mono">{{ $kk['no_kk'] }}</span></div>
                                        <div><span class="font-bold text-slate-500">Alamat:</span> {{ $kk['alamat'] }}</div>
                                        <div><span class="font-bold text-slate-500">Jumlah Anggota:</span> {{ $kk['jumlah'] }} orang</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 sm:p-8">
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Daftar Anggota Keluarga</div>
                            <div class="space-y-2">
                                @foreach ($kk['anggota'] as $a)
                                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                        <div class="min-w-0 flex-1">
                                            <div class="font-bold text-sm text-slate-900 truncate">{{ $a['nama'] }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">NIK: <span class="font-mono">{{ $a['nik'] }}</span></div>
                                        </div>
                                        <div class="text-right ml-3">
                                            <div class="text-[10px] font-bold text-brand-900 bg-brand-50 px-2 py-0.5 rounded-md uppercase tracking-wider">{{ $a['status'] }}</div>
                                            <div class="text-[10px] text-slate-400 mt-1">{{ $a['jk'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-4 flex items-center justify-between">
                        <div class="text-sm text-slate-600">
                            Ditemukan <span class="font-bold text-brand-900">{{ count($results) }}</span> hasil
                        </div>
                    </div>
                    <div class="space-y-3">
                        @foreach ($results as $r)
                            <div class="bg-white rounded-2xl border border-slate-100 p-5 hover:border-gold-500/50 hover:shadow-lg hover:shadow-brand-900/5 transition-all">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-slate-900 mb-1">{{ $r['nama'] }}</div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1 text-xs text-slate-500">
                                            <div>NIK: <span class="font-mono text-slate-700">{{ $r['nik'] }}</span></div>
                                            <div>No. KK: <span class="font-mono text-slate-700">{{ $r['kk'] }}</span></div>
                                            <div class="sm:col-span-2">Alamat: <span class="text-slate-700">{{ $r['alamat'] }}</span></div>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-md uppercase tracking-wider flex-shrink-0 {{ $r['jk'] === 'Laki-laki' || $r['jk'] === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                        {{ $r['jk'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="bg-white rounded-3xl border border-slate-100 py-16 px-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-900 mb-1">Data tidak ditemukan</p>
                    <p class="text-sm text-slate-500">Periksa kembali kata kunci pencarian Anda.</p>
                </div>
            @endif

            <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-100 flex gap-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs text-amber-900 leading-relaxed">
                    <span class="font-bold">Catatan:</span> NIK dan No. KK hanya ditampilkan 4 digit terakhir untuk menjaga keamanan data pribadi. Untuk data lengkap, silakan datang langsung ke Kantor Desa dengan membawa KTP/KK asli.
                </p>
            </div>
        </section>
    @endif
</div>
