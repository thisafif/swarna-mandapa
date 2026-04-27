@props(['feature', 'delay' => 0])

<article class="flex items-center gap-6" data-reveal data-reveal-delay="{{ $delay }}">
    <span class="grid size-7 shrink-0 place-items-center text-[#c5a858]" aria-hidden="true">
        <i class="{{ $feature['icon'] }} fa-fw text-[28px] leading-none"></i>
    </span>
    <span class="min-w-0 text-[#71562a]">
        <strong class="block font-serif text-2xl leading-snug">{{ $feature['title'] }}</strong>
        <span class="block text-base leading-snug">{{ $feature['text'] }}</span>
    </span>
</article>
