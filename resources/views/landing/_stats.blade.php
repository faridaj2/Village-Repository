{{-- ============ STATS ============ --}}
<section class="relative -mt-24 z-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_24px_60px_-30px_rgba(6,78,59,0.35)] p-6 sm:p-8 lg:p-10 grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @php
                $statCards = [
                    ['label' => 'Penduduk Aktif', 'key' => 'penduduk', 'icon' => 'users', 'accent' => 'emerald'],
                    ['label' => 'Kartu Keluarga', 'key' => 'kk', 'icon' => 'home', 'accent' => 'gold'],
                    ['label' => 'Surat Terbit', 'key' => 'surat', 'icon' => 'mail', 'accent' => 'emerald'],
                    ['label' => 'Total Rumah', 'key' => 'rumah', 'icon' => 'building', 'accent' => 'gold'],
                ];
            @endphp

            @foreach ($statCards as $i => $s)
                <div class="reveal delay-{{ $i % 4 }} flex items-start gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center flex-shrink-0
                        {{ $s['accent'] === 'gold' ? 'bg-gold-50' : 'bg-brand-50' }}">
                        @if($s['icon'] === 'users')
                            <svg class="w-6 h-6 {{ $s['accent'] === 'gold' ? 'text-gold-600' : 'text-brand-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        @elseif($s['icon'] === 'home')
                            <svg class="w-6 h-6 {{ $s['accent'] === 'gold' ? 'text-gold-600' : 'text-brand-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        @elseif($s['icon'] === 'mail')
                            <svg class="w-6 h-6 {{ $s['accent'] === 'gold' ? 'text-gold-600' : 'text-brand-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        @else
                            <svg class="w-6 h-6 {{ $s['accent'] === 'gold' ? 'text-gold-600' : 'text-brand-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="stat-number text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-none mb-1.5" data-counter="{{ $stats[$s['key']] }}">0</p>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium truncate">{{ $s['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
