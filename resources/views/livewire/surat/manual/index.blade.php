<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Surat Manual</h2>
                <p class="text-sm text-gray-500">Buat surat bebas tanpa template</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('surat.manual.blok') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Blok Kode
                </a>
                <a href="{{ route('surat.manual.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                    + Buat Surat
                </a>
            </div>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-4">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama surat..." class="w-full sm:w-80 px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
        </div>

        @if ($surats->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ukuran</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($surats as $surat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $surat->nama }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 uppercase">{{ $surat->paper_size }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $surat->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('surat.manual.edit', $surat->id) }}" class="px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Edit</a>
                                        <button wire:click="delete({{ $surat->id }})" wire:confirm="Yakin hapus surat ini?" class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $surats->links() }}
            </div>
        @else
            <p class="text-sm text-gray-500 text-center py-8">Belum ada surat manual. <a href="{{ route('surat.manual.create') }}" class="text-indigo-600 hover:underline">Buat sekarang</a>.</p>
        @endif
    </div>
</div>
