@props(['testimonial', 'delay' => 0])

<article
    class="mb-6 inline-block w-full break-inside-avoid rounded-2xl border border-[#e4dcc8] bg-[#fefdf9] p-4 font-serif text-base leading-snug text-[#71562a]"
    data-reveal
    data-reveal-delay="{{ $delay }}"
>
    <p class="font-bold">“</p>
    <p>{{ $testimonial['text'] }}</p>
    <strong class="mt-4 block border-t border-[#e4dcc8] pt-2 text-[#c5a858]">- {{ $testimonial['name'] }}</strong>
</article>
