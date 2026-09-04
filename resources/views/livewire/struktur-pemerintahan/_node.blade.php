@php
    $children = $strukturList->where('parent_id', $item->id)->sortBy('urutan');
    $isRoot = $depth === 0;
@endphp

<div class="flex flex-col items-center">
    <div class="flex flex-col items-center">
        <div class="{{ $isRoot ? 'w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30' : 'w-16 h-16 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 shadow' }} flex items-center justify-center overflow-hidden">
            @if ($item->foto)
                <img src="{{ $item->foto }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
            @else
                <span class="{{ $isRoot ? 'text-2xl font-bold text-white' : 'text-lg font-bold text-gray-500' }}">{{ substr($item->nama, 0, 1) }}</span>
            @endif
        </div>
        <div class="mt-3 text-center">
            <p class="{{ $isRoot ? 'text-sm font-bold' : 'text-xs font-semibold' }} text-gray-900 leading-tight">{{ $item->nama }}</p>
            <p class="{{ $isRoot ? 'text-xs font-medium text-indigo-600' : 'text-[11px] text-gray-500' }} mt-0.5">{{ $item->jabatan }}</p>
        </div>
    </div>

    @if ($children->count() > 0)
        <div class="w-px h-6 bg-gray-300"></div>
        <div class="flex flex-wrap justify-center gap-6">
            @foreach ($children as $child)
                @include('livewire.struktur-pemerintahan._node', ['item' => $child, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</div>
