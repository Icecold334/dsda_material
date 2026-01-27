@props(['variant' => 'primary', 'type' => 'button'])

@php
    $classes = [
        'primary' => 'bg-gray-800 text-white hover:bg-gray-700 active:bg-gray-900 focus:ring-indigo-500',
        'secondary' => 'bg-white text-gray-700  border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-500 active:bg-red-700 focus:ring-red-500',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-400 active:bg-yellow-600 focus:ring-yellow-500',
        'success' => 'bg-green-600 text-white hover:bg-green-500 active:bg-green-700 focus:ring-green-500',
        'info' => 'bg-blue-600 text-white hover:bg-blue-500 active:bg-blue-700 focus:ring-blue-500',
        'dark' => 'bg-gray-900 text-white hover:bg-gray-800 active:bg-black focus:ring-gray-700',
    ];

    $baseClasses = 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150';

    $variantClasses = $classes[$variant] ?? $classes['primary'];

    // Add shadow for secondary variant
    if ($variant === 'secondary') {
        $baseClasses .= ' shadow-sm';
    }
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $baseClasses . ' ' . $variantClasses]) }}>
    {{ $slot }}
</button>