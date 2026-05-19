@props([
    'title',
    'subtitle' => null,
    'level' => 'h2',
    'align' => 'center',
])

@php
    $headingClasses = $level === 'h3'
        ? 'font-serif text-3xl font-bold leading-tight text-[#c5a858] sm:text-4xl'
        : 'font-serif text-[32px] font-bold leading-[1.25] text-[#c5a858] sm:text-5xl';
@endphp

<div
    {{ $attributes->class([
        'grid gap-4',
        'text-center' => $align === 'center',
        'text-left' => $align === 'left',
    ]) }}
    data-reveal
>
    @if ($level === 'h3')
        <h3 class="{{ $headingClasses }}">{{ $title }}</h3>
    @else
        <h2 class="{{ $headingClasses }}">{{ $title }}</h2>
    @endif
    @if ($subtitle)
        <p class="mx-auto max-w-[1296px] whitespace-pre-line font-serif text-lg font-bold leading-snug text-[#71562a] sm:text-2xl">
            {{ $subtitle }}
        </p>
    @endif
</div>
