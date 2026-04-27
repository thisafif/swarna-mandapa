@props([
    'variant' => 'dark',
    'href' => '#home',
    'asset' => asset('assets/swarna/logo.png'),
])

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'inline-flex items-center gap-2 no-underline',
        'text-white' => $variant === 'light',
        'text-[#c5a858]' => $variant !== 'light',
    ]) }}
    aria-label="Swarna Mandapa home"
>
    <img src="{{ $asset }}" alt="" class="h-[60px] w-[183px] object-contain">
</a>
