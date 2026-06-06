@props(['links' => []])

<footer id="contact" class="grid gap-12 bg-[#775c31] px-5 py-12 text-white sm:px-10 lg:min-h-[431px] lg:grid-cols-2 lg:items-center lg:px-[72px] lg:py-[72px]">
    <div class="grid gap-8 lg:gap-[77px]">
        <div class="grid gap-2">
            <x-brand variant="light" href="{{ url('/') }}" />
            <p class="font-serif text-base">A golden sanctuary where tradition and luxury live in perfect harmony.</p>
        </div>
        <div class="grid gap-2 font-serif">
            <h2 class="text-2xl font-bold">Contact Us</h2>
            <p class="flex gap-3">
                <span class="mt-1 text-white" aria-hidden="true"><i class="fa-solid fa-location-dot fa-fw text-[24px] leading-none"></i></span>
                <span>Jl. Nuansa Angkasa III No.7 & 9, Ungasan, Kec. Kuta Sel., Kabupaten Badung, Bali 80361, Indonesia</span>
            </p>
            <p class="flex flex-wrap gap-x-12 gap-y-3">
                <a href="tel:+64272973575" class="inline-flex items-center gap-3 !text-white !no-underline hover:!text-[#ffdc7d]"><i class="fa-solid fa-phone fa-fw" aria-hidden="true"></i> +64 27 297 3575</a>
                <a href="mailto:reservations@swarnamandapa.com" class="inline-flex items-center gap-3 !text-white !no-underline hover:!text-[#ffdc7d]"><i class="fa-solid fa-envelope fa-fw" aria-hidden="true"></i> reservations@swarnamandapa.com</a>
            </p>
        </div>
    </div>
    <div class="grid gap-12 lg:justify-items-end">
        <x-footer-nav :links="$links" />
        <div class="grid gap-4">
            <h2 class="font-serif font-bold">Social Media</h2>
            <div class="flex gap-4" aria-label="Social media links">
                <a href="https://www.instagram.com/swarnamandapa?igsh=d3dwY3diOTNqbHhz" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="grid size-[42px] place-items-center rounded-full border border-white/60 text-xl !text-white !no-underline transition hover:border-[#ffdc7d] hover:!text-[#ffdc7d]"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                <a href="https://wa.me/64272973575" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" class="grid size-[42px] place-items-center rounded-full border border-white/60 text-xl !text-white !no-underline transition hover:border-[#ffdc7d] hover:!text-[#ffdc7d]"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></a>
                <a href="mailto:reservations@swarnamandapa.com" aria-label="Email" class="grid size-[42px] place-items-center rounded-full border border-white/60 text-lg !text-white !no-underline transition hover:border-[#ffdc7d] hover:!text-[#ffdc7d]"><i class="fa-solid fa-envelope" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</footer>
