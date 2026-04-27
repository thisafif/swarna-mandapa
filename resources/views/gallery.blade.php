@php
    $galleryAsset = fn (string $folder, string $name) => asset("assets/gallery/{$folder}/" . rawurlencode($name));

    $tabs = [
        [
            'id' => 'grand-living-spaces',
            'label' => 'Grand living spaces',
            'folder' => '01grand-living-spaces',
            'slogan' => 'Luxury, comfort and authenticity.',
            'description' => 'The living spaces at Swarna Mandapa are grand, light-filled, and designed to impress. Ornate detailing and golden accents frame expansive open-plan interiors that flow effortlessly to the outdoors. Plush furnishings, high ceilings, and statement decor create an atmosphere that is both luxurious and welcoming, ideal for entertaining or simply enjoying the sense of space and elegance.',
        ],
        [
            'id' => 'grand-master-suite',
            'label' => 'Grand master suite',
            'folder' => '02grand-master-suite',
            'slogan' => null,
            'description' => 'The Grand Master Suite (150 sq meters) is a lavish private retreat that captures the essence of Swarna Mandapa\'s opulent style. It features a designer Super King bed, private lounge with Smart TV, and an exquisite ensuite with a freestanding clawfoot bath and separate shower. Intricate gold detailing, high ceilings, and a private deck overlooking the lush surroundings make this suite a truly spectacular space to unwind in luxury.',
        ],
        [
            'id' => 'master-guest-suite',
            'label' => 'Master guest suite',
            'folder' => '03master-guest-suite',
            'slogan' => null,
            'description' => 'The Master Guest Suite (90 sq meters) offers a refined sanctuary of comfort and grandeur. Featuring a designer Super King bed, elegant decor, and large windows that open to a private outdoor area, it blends rich textures with serene views. Every element, from the intricate finishes to the generous proportions, reflects the home\'s unmistakable sense of luxury.',
        ],
        [
            'id' => 'guest-suite',
            'label' => 'Guest suite',
            'folder' => '04guest-suite',
            'slogan' => null,
            'description' => 'Each of the three Guest Suites embodies Swarna Mandapa\'s signature sense of scale and style. With super comfy King beds, private ensuite bathrooms, and beautifully crafted details, these suites offer guests an indulgent experience of their own. Ornate finishes and sumptuous comfort make each room a statement in luxury living.',
        ],
        [
            'id' => 'outdoor-elegance',
            'label' => 'Outdoor Elegance',
            'folder' => '05outdoor-elegance',
            'slogan' => 'Privacy and security with a resort style pool area.',
            'description' => 'The residence opens to a 10-meter swimming pool, framed by vibrant tropical gardens and colourful blooms, making this area a striking focal point with a resort-style atmosphere. The expansive terrace, elegant loungers, and glistening water create the perfect spot for sun-soaked days and sunset gatherings. The estate is encircled by intricately carved high concrete and stone walls, with grand 12-foot carved gates providing both privacy and a stunning display of craftsmanship.',
        ],
        [
            'id' => 'the-heart-of-the-home',
            'label' => 'The heart of the Home',
            'folder' => '06the-heart-of-the-home',
            'slogan' => 'A Chef\'s Paradise.',
            'description' => 'At the center of Swarna Mandapa lies a spectacular gourmet kitchen designed to inspire culinary creativity. The highlight is an exquisite Italian marble island bench, with rich shimmering tones of blue, gold, and black echoing the property\'s opulent character. Surrounding it are top-quality appliances, seamlessly integrated into bespoke cabinetry. It is a kitchen that is as functional as it is beautiful, a true statement of refined taste.',
        ],
    ];

    $bentoClasses = [
        'sm:col-span-2 sm:row-span-2 lg:col-span-4 lg:row-span-2',
        'sm:col-span-1 sm:row-span-1 lg:col-span-2 lg:row-span-1',
        'sm:col-span-1 sm:row-span-1 lg:col-span-2 lg:row-span-1',
        'sm:col-span-2 sm:row-span-1 lg:col-span-3 lg:row-span-1',
        'sm:col-span-1 sm:row-span-1 lg:col-span-3 lg:row-span-1',
        'sm:col-span-1 sm:row-span-1 lg:col-span-2 lg:row-span-1',
        'sm:col-span-2 sm:row-span-1 lg:col-span-4 lg:row-span-1',
        'sm:col-span-1 sm:row-span-1 lg:col-span-3 lg:row-span-1',
        'sm:col-span-1 sm:row-span-1 lg:col-span-3 lg:row-span-1',
    ];

    $footerLinks = [
        'Home' => url('/'),
        'Features' => url('/#features'),
        'Gallery' => route('gallery'),
        'Reviews' => route('reviews'),
        'Contact Us' => route('contact-us'),
    ];

    foreach ($tabs as $index => $tab) {
        $folderPath = public_path("assets/gallery/{$tab['folder']}");
        $images = collect(glob("{$folderPath}/*") ?: [])
            ->filter(fn ($path) => is_file($path) && in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'avif']))
            ->map(function ($path) use ($tab, $galleryAsset) {
                $filename = basename($path);
                $name = pathinfo($filename, PATHINFO_FILENAME);
                $alt = str($name)
                    ->replace(['Swarna+Mandapa+-+', '+', '-', '_'], ['Swarna Mandapa ', ' ', ' ', ' '])
                    ->squish()
                    ->toString();

                return [
                    'src' => $galleryAsset($tab['folder'], $filename),
                    'alt' => $alt,
                ];
            })
            ->values()
            ->all();

        $tabs[$index]['images'] = $images;
    }
@endphp

<x-layout title="Gallery | Swarna Mandapa">
    <x-site-header />

    <main class="overflow-hidden bg-white pt-[80px] sm:pt-[96px]">
        <section class="px-5 py-8 pt-10 sm:px-10 sm:pb-16 sm:pt-14 lg:px-20 lg:pb-20 lg:pt-[82px]">
            <div class="mx-auto grid w-full min-w-0 max-w-[1280px] justify-items-center">
                <div class="mb-8 grid w-full min-w-0 max-w-[820px] justify-items-center gap-3 text-center sm:mb-10" data-reveal>
                    <div class="flex items-center justify-center gap-3 text-[#b8892e]/70" aria-hidden="true">
                        <span class="h-px w-10 bg-current"></span>
                        <span class="font-serif text-xs leading-none tracking-[0.14em]">✦</span>
                        <span class="h-px w-10 bg-current"></span>
                    </div>
                    <p class="max-w-full break-words font-serif text-xs uppercase tracking-[0.18em] text-[#b8892e] sm:text-[18px] sm:tracking-[0.28em]">Signature Gallery</p>
                    <h1 class="max-w-full text-wrap font-serif text-[34px] font-normal leading-tight text-[#1e1408] sm:text-[54px]">
                        Explore Every <em class="font-normal text-[#9e6e42]">Golden Detail</em>
                    </h1>
                    <p class="max-w-[700px] text-wrap font-serif text-base leading-[1.8] text-[#6a5438] sm:text-[17px]">
                        Move through Swarna Mandapa room by room, from grand gathering spaces to private suites, tropical pool edges, and the gourmet kitchen at the heart of the home.
                    </p>
                </div>

                <div class="w-full min-w-0" data-gallery-tabs>
                    <div class="mx-auto mb-8 flex w-full min-w-0 max-w-[1120px] snap-x gap-3 overflow-x-auto border-b border-[#e4dcc8] py-8 sm:mb-10 lg:flex-wrap lg:justify-center lg:overflow-visible" role="tablist" aria-label="Gallery categories">
                        @foreach ($tabs as $tab)
                            <button
                                type="button"
                                id="tab-{{ $tab['id'] }}"
                                class="max-w-[82vw] shrink-0 snap-start whitespace-nowrap rounded-full border px-4 py-2.5 font-serif text-[13px] font-bold transition focus:outline-none focus:ring-2 focus:ring-[#b8892e] focus:ring-offset-2 sm:max-w-none sm:px-5 sm:py-3 sm:text-sm {{ $loop->first ? 'border-[#b8892e] bg-[#b8892e] text-white shadow-[0_4px_12px_rgba(184,137,46,0.22)]' : 'border-[#e4dcc8] bg-[#fefdf9] text-[#71562a] hover:border-[#c5a858] hover:text-[#9e6e42]' }}"
                                role="tab"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="panel-{{ $tab['id'] }}"
                                data-gallery-tab="{{ $tab['id'] }}"
                            >
                                {{ $tab['label'] }}
                            </button>
                        @endforeach
                    </div>

                    @foreach ($tabs as $tab)
                        <section
                            id="panel-{{ $tab['id'] }}"
                            class="{{ $loop->first ? 'grid' : 'hidden' }} w-full min-w-0 gap-7 sm:gap-8"
                            role="tabpanel"
                            aria-labelledby="tab-{{ $tab['id'] }}"
                            data-gallery-panel="{{ $tab['id'] }}"
                        >
                            <div class="mx-auto grid w-full min-w-0 max-w-[920px] gap-3 text-center">
                                @if ($tab['slogan'])
                                    <p class="max-w-full break-words font-serif text-[10px] font-bold uppercase tracking-[0.12em] text-[#b8892e] sm:text-[11px] sm:tracking-[0.22em]">{{ $tab['slogan'] }}</p>
                                @endif
                                <h2 class="text-wrap font-serif text-[30px] font-bold leading-tight text-[#c5a858] sm:text-[40px] lg:text-5xl">{{ $tab['label'] }}</h2>
                                <p class="whitespace-pre-line text-wrap font-serif text-sm leading-relaxed text-[#71562a] sm:text-base lg:text-lg py-4">{{ $tab['description'] }}</p>
                            </div>

                            @if (count($tab['images']) > 0)
                                <div class="mx-auto grid w-full max-w-[1296px] grid-cols-1 gap-4 sm:grid-cols-2 sm:[grid-auto-rows:190px] md:[grid-auto-rows:220px] lg:grid-cols-6 lg:gap-6 lg:[grid-auto-rows:248px]">
                                    @foreach ($tab['images'] as $image)
                                        <figure class="{{ $bentoClasses[$loop->index % count($bentoClasses)] }} group relative h-[250px] overflow-hidden rounded-2xl bg-[#fefdf9] sm:h-full" data-reveal data-reveal-delay="{{ ($loop->index % 6) * 70 }}">
                                            <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}" class="size-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-[#2a1a08]/70 via-[#2a1a08]/15 to-transparent opacity-80"></div>
                                        </figure>
                                    @endforeach
                                </div>
                            @else
                                <p class="mx-auto max-w-[720px] rounded-2xl border border-[#e4dcc8] bg-[#fefdf9] p-8 text-center font-serif text-lg text-[#71562a]">
                                    Images for this gallery category are coming soon.
                                </p>
                            @endif
                        </section>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid justify-items-center gap-6 border border-[#e4dcc8] bg-[#fefdf9] px-5 py-14 text-center sm:px-10 lg:px-[300px] lg:py-[96px]" data-reveal>
            <h2 class="font-serif text-[32px] font-bold leading-tight text-[#c5a858] sm:text-5xl">
                Ready to Experience<br class="hidden sm:block"> Swarna Mandapa in Person?
            </h2>
            <p class="font-serif text-lg font-bold text-[#71562a] sm:text-2xl">Let our concierge help you plan the perfect stay.</p>
            <x-gold-button href="{{ route('contact-us') }}" class="w-full sm:w-auto sm:px-12">Check Availability →</x-gold-button>
        </section>

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
                        <a href="tel:+64272973575" class="inline-flex items-center gap-3 text-white no-underline hover:text-[#ffdc7d]"><i class="fa-solid fa-phone fa-fw" aria-hidden="true"></i> +64 27 297 3575</a>
                        <a href="mailto:reservations@swarnamandapa.com" class="inline-flex items-center gap-3 text-white no-underline hover:text-[#ffdc7d]"><i class="fa-solid fa-envelope fa-fw" aria-hidden="true"></i> reservations@swarnamandapa.com</a>
                    </p>
                </div>
            </div>
            <div class="grid gap-12 lg:justify-items-end">
                <x-footer-nav :links="$footerLinks" />
                <div class="grid gap-4">
                    <h2 class="font-serif font-bold">Social Media</h2>
                    <div class="flex gap-4" aria-label="Social media links">
                        <a href="#" aria-label="Instagram" class="grid size-[42px] place-items-center rounded-full border border-white/60 text-xl text-white no-underline transition hover:border-[#ffdc7d] hover:text-[#ffdc7d]"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                        <a href="#" aria-label="TikTok" class="grid size-[42px] place-items-center rounded-full border border-white/60 text-lg text-white no-underline transition hover:border-[#ffdc7d] hover:text-[#ffdc7d]"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
                        <a href="#" aria-label="Facebook" class="grid size-[42px] place-items-center rounded-full border border-white/60 text-lg text-white no-underline transition hover:border-[#ffdc7d] hover:text-[#ffdc7d]"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    </main>
</x-layout>
