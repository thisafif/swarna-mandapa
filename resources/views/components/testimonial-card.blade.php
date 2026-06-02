@props(['testimonial', 'delay' => 0])

<div
    class="mb-6 inline-block w-full break-inside-avoid"
    data-reveal
    data-reveal-delay="{{ $delay }}"
>
    <article class="h-full w-full rounded-2xl border border-[#e4dcc8] bg-[#fefdf9] p-4 font-serif text-base leading-snug text-[#71562a] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(42,26,8,0.08)] hover:border-[#c5a858]/40 active:scale-[0.98] cursor-pointer">
        <p class="font-bold">“</p>
        <p>{{ $testimonial['text'] }}</p>
        <strong class="mt-4 block border-t border-[#e4dcc8] pt-2 text-[#c5a858]">- {{ $testimonial['name'] }}</strong>
    </article>
</div>
