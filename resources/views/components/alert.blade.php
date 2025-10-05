@props(['type'])

@php
    // Tailwind classes for different alert types
    $colors = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-400 text-blue-700',
    ];

    $color = $colors[$type] ?? $colors['info'];
@endphp

@if (session($type))
    <div id="alert" class="{{ $color }} border px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold capitalize">{{ $type }}!</strong>
        <span class="block sm:inline">{{ session($type) }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <button type="button" onclick="this.parentElement.parentElement.remove();">
                <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 5.652a1 1 0 00-1.414-1.414L10 7.172 7.066 4.238a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 12.828l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934z"/>
                </svg>
            </button>
        </span>
    </div>

    <script>
        setTimeout(() => {
            const alert = document.getElementById('alert');
            if (alert) alert.remove();
        }, 3000);
    </script>
@endif
