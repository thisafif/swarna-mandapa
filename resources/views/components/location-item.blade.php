@props(['text', 'icon' => 'fa-solid fa-location-dot', 'delay' => 0])

<div class="flex items-start gap-4 font-serif text-base leading-snug text-[#71562a] sm:text-lg" data-reveal data-reveal-delay="{{ $delay }}">
    <span class="grid size-7 shrink-0 place-items-center text-[#c5a858]" aria-hidden="true">
        <i class="{{ $icon }} fa-fw text-[28px] leading-none"></i>
    </span>
    <p class="m-0">{{ $text }}</p>
</div>
