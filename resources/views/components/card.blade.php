<div {{ $attributes->merge(['class' => 'bg-white/70 backdrop-blur-xl border border-white/70 rounded-3xl shadow-xl shadow-slate-200/50 p-4 sm:p-6']) }}>
    @if($title ?? null)
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>
