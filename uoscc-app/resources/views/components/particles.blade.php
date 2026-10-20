@props(['variant' => 'green'])

<div {{ $attributes->merge(['class' => 'pointer-events-none absolute inset-0 overflow-hidden']) }} aria-hidden="true">
    @foreach([
        [8, 20, 3, 6, 0], [22, 55, 4, 8, 1.2], [36, 15, 3, 7, 2.1],
        [50, 65, 4, 9, 0.6], [64, 30, 3, 6, 1.8], [78, 60, 4, 8, 2.6],
        [90, 25, 3, 7, 0.3], [15, 75, 3, 9, 3.2], [45, 40, 5, 10, 1.5],
        [70, 82, 3, 7, 2.8],
    ] as [$left, $top, $size, $duration, $delay])
        <span @class(['particle', 'particle-light' => $variant === 'light', 'particle-neon' => $variant === 'neon']) style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $size }}px; height: {{ $size }}px; animation-duration: {{ $duration }}s; animation-delay: {{ $delay }}s;"></span>
    @endforeach
</div>
