<div class="min-h-screen bg-[#F2F2F7]">
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="text-lg font-semibold text-gray-900">SIDESA</span>
            </a>
            <nav class="flex gap-4">
                <a href="{{ route('pengumuman.publik') }}" class="text-sm text-blue-500 font-medium">Pengumuman</a>
                <a href="{{ route('surat.ajukan') }}" class="text-sm text-gray-500 hover:text-gray-700">Ajukan Surat</a>
                <a href="{{ route('surat.tracking') }}" class="text-sm text-gray-500 hover:text-gray-700">Tracking Surat</a>
            </nav>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Pengumuman Desa</h1>

        <div class="space-y-4">
            @forelse ($pengumumans as $item)
                <x-card>
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $item->judul }}</h2>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->tanggal_publish->format('d F Y') }}</p>
                        </div>
                        @if ($item->kategori)
                            <x-badge variant="info">{{ $item->kategori }}</x-badge>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 mt-3 whitespace-pre-line">{{ $item->isi }}</p>
                </x-card>
            @empty
                <x-card>
                    <p class="text-center text-gray-500 py-8">Belum ada pengumuman.</p>
                </x-card>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $pengumumans->links() }}
        </div>
    </main>
</div>
