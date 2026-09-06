@php
    $children = $strukturList->where('parent_id', $item->id);
    $isRoot = $depth === 0;
    $hasChildren = $children->count() > 0;
    $childCount = $children->count();

    // Calculate minimum width for this node
    if (!$hasChildren) {
        $nodeMinWidth = 120;
    } elseif ($childCount === 1) {
        $nodeMinWidth = 160;
    } else {
        $nodeMinWidth = max($childCount * 160, 300);
    }
@endphp

<div style="display: inline-flex; flex-direction: column; align-items: center; min-width: {{ $nodeMinWidth }}px; padding: 0 4px;">
    <!-- Card -->
    <div style="width: {{ $isRoot ? '72px' : '56px' }}; height: {{ $isRoot ? '72px' : '56px' }}; border-radius: 50%; background: {{ $isRoot ? 'linear-gradient(135deg, #6366f1, #8b5cf6)' : 'linear-gradient(135deg, #f3f4f6, #e5e7eb)' }}; display: flex; align-items: center; justify-content: center; box-shadow: {{ $isRoot ? '0 4px 16px rgba(99,102,241,0.35)' : '0 2px 8px rgba(0,0,0,0.08)' }}; position: relative; z-index: 1; flex-shrink: 0;">
        @if ($item->foto)
            <img src="{{ $item->foto }}" alt="{{ $item->nama }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
        @else
            <span style="font-weight: {{ $isRoot ? 'bold; font-size: 20px; color: white' : '700; font-size: 14px; color: #6b7280' }};">{{ substr($item->nama, 0, 1) }}</span>
        @endif
    </div>
    <div style="text-align: center; margin-top: 8px; flex-shrink: 0;">
        <p style="font-size: {{ $isRoot ? '14px' : '12px' }}; font-weight: {{ $isRoot ? '700' : '600' }}; color: #111827; line-height: 1.3; white-space: nowrap;">{{ $item->nama }}</p>
        <p style="font-size: {{ $isRoot ? '12px' : '11px' }}; color: {{ $isRoot ? '#6366f1' : '#6b7280' }}; white-space: nowrap;">{{ $item->jabatan }}</p>
    </div>

    @if ($hasChildren)
        <div style="display: flex; flex-direction: column; align-items: center; margin-top: 8px; width: 100%;">
            {{-- Vertical line from parent down --}}
            <div style="width: 2px; height: 20px; background: #d1d5db; flex-shrink: 0;"></div>

            @if ($childCount === 1)
                {{-- Single child: just vertical line, no horizontal --}}
                @include('livewire.struktur-pemerintahan._node', ['item' => $children->first(), 'depth' => $depth + 1])
            @else
                {{-- Multiple children: each child draws its own horizontal segment --}}
                <div style="display: flex; align-items: flex-start; width: 100%;">
                    @foreach ($children as $index => $child)
                        @php
                            $isFirst = $index === 0;
                            $isLast = $index === $childCount - 1;
                        @endphp
                        <div style="display: inline-flex; flex-direction: column; align-items: center; flex: 1; min-width: 160px; position: relative;">
                            {{-- Horizontal line segment: first=right half, middle=full, last=left half --}}
                            @if ($isFirst)
                                <div style="position: absolute; top: 0; left: 50%; right: 0; height: 2px; background: #d1d5db;"></div>
                            @elseif ($isLast)
                                <div style="position: absolute; top: 0; left: 0; right: 50%; height: 2px; background: #d1d5db;"></div>
                            @else
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: #d1d5db;"></div>
                            @endif

                            {{-- Vertical drop from horizontal line --}}
                            <div style="width: 2px; height: 20px; background: #d1d5db; flex-shrink: 0;"></div>

                            @include('livewire.struktur-pemerintahan._node', ['item' => $child, 'depth' => $depth + 1])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
