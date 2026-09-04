@props(['variant' => 'primary', 'size' => 'md'])

@php
    $base = 'inline-flex items-center justify-center font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500';
    
    $variantClasses = match($variant) {
        'primary' => 'bg-blue-500 text-white hover:bg-blue-600',
        'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300',
        'danger' => 'bg-red-500 text-white hover:bg-red-600',
        'outline' => 'border border-gray-300 text-gray-700 hover:bg-gray-50',
        default => 'bg-blue-500 text-white hover:bg-blue-600',
    };
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm rounded-lg',
        'md' => 'px-4 py-2 text-sm rounded-xl',
        'lg' => 'px-6 py-3 text-base rounded-xl',
        default => 'px-4 py-2 text-sm rounded-xl',
    };
@endphp

<button {{ $attributes->merge(['class' => "$base $variantClasses $sizeClasses"]) }}>
    {{ $slot }}
</button>
