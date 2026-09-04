@props(['label' => '', 'error' => '', 'options' => []])

<div class="space-y-1">
    @if($label)
        <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <select {{ $attributes->merge(['class' => 'block w-full rounded-xl border-0 bg-gray-100 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6']) }}>
        {{ $slot }}
    </select>
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
