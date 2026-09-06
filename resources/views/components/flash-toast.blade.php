@php
    $flashMessages = [];

    foreach ([
        'success' => ['message', 'success'],
        'error' => ['error'],
        'warning' => ['warning'],
    ] as $type => $keys) {
        foreach ($keys as $key) {
            if (session()->has($key) && trim((string) session($key)) !== '') {
                $flashMessages[] = [
                    'type' => $type,
                    'text' => session($key),
                ];
                break;
            }
        }
    }
@endphp

@if (count($flashMessages) > 0)
    <div class="fixed inset-x-0 bottom-4 sm:bottom-6 z-[100] flex flex-col items-center gap-2 px-4 sm:items-end sm:pr-6">
        @foreach ($flashMessages as $index => $toast)
            @php
                $styles = [
                    'success' => 'bg-white border-emerald-200 text-emerald-800 [&>svg]:text-emerald-500 shadow-lg shadow-emerald-500/10',
                    'error' => 'bg-white border-red-200 text-red-800 [&>svg]:text-red-500 shadow-lg shadow-red-500/10',
                    'warning' => 'bg-white border-amber-200 text-amber-800 [&>svg]:text-amber-500 shadow-lg shadow-amber-500/10',
                ];
                $icons = [
                    'success' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'error' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'warning' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                ];
            @endphp
            <div x-data="{ show: true, leaving: false }"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 x-init="setTimeout(() => { leaving = true; show = false; }, 4500)"
                 class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-2xl border p-4 {{ $styles[$toast['type']] }}">
                {!! $icons[$toast['type']] !!}
                <p class="flex-1 text-sm font-medium leading-snug">{{ $toast['text'] }}</p>
                <button type="button" @click="show = false" class="-m-1 p-1 text-gray-400 hover:text-gray-600 transition-colors" aria-label="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endforeach
    </div>
@endif
