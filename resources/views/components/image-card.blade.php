@props([
    'src',
    'alt' => '',
    'class' => '',
    'delay' => 0,
])

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    class="h-full w-full rounded-2xl object-cover {{ $class }}"
    loading="lazy"
    data-reveal
    data-reveal-delay="{{ $delay }}"
>
