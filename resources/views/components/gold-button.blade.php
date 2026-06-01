@props(['href' => null])

@php
    $classes = 'inline-flex min-h-[62px] items-center justify-center rounded-[32px] bg-gradient-to-r from-[#9a7b3e] to-[#c5a858] px-6 py-4 text-center font-serif text-xl font-bold leading-none text-white !no-underline transition duration-300 hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-[#c5a858] focus:ring-offset-2 sm:text-2xl';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="button" {{ $attributes->class($classes) }}>{{ $slot }}</button>
@endif
