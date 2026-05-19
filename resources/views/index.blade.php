@php
    $asset = fn (string $name) => asset("assets/swarna/{$name}");

    $features = [
        ['icon' => 'fa-solid fa-utensils', 'title' => 'Gourmet Kitchen', 'text' => 'With stunning Italian marble island and top-quality appliances.'],
        ['icon' => 'fa-solid fa-tv', 'title' => 'Entertainment', 'text' => '72-inch Smart TV in lounge, Smart TV in Grand Master Suite.'],
        ['icon' => 'fa-solid fa-door-open', 'title' => 'Two large balconies', 'text' => 'Perfect for outdoor relaxation whatever the weather.'],
        ['icon' => 'fa-solid fa-water-ladder', 'title' => '10 Metre Swimming Pool', 'text' => 'With waterfall feature and surrounded by tropical gardens and vibrant blooms.'],
        ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Security', 'text' => 'Secure private gates and intricately carved high concrete walls.'],
    ];

    $moreFeatures = [
        ['icon' => 'fa-solid fa-lock', 'title' => 'Privacy', 'text' => 'Fully enclosed estate with 12-foot ornate carved gates.'],
        ['icon' => 'fa-solid fa-wind', 'title' => 'Climate Control', 'text' => '7 air-conditioning units and multiple ceiling fans throughout.'],
        ['icon' => 'fa-solid fa-wifi', 'title' => 'Connectivity', 'text' => 'High-speed Wi-Fi throughout the property.'],
        ['icon' => 'fa-solid fa-lightbulb', 'title' => 'Atmosphere', 'text' => 'Every corner tells a story with carved doors and gilded columns.'],
        ['icon' => 'fa-solid fa-ban', 'title' => 'No parties or events', 'text' => 'Above all else this is a sanctuary designed for peaceful enjoyment and relaxation.'],
    ];

    $nearby = [
        ['icon' => 'fa-solid fa-umbrella-beach', 'text' => '5 km to Melasti Beach: Bali’s best swimming beach.'],
        ['icon' => 'fa-solid fa-umbrella-beach', 'text' => '5 km to top beach clubs: Sundays, White Rock, Karma, and Savaya'],
        ['icon' => 'fa-solid fa-umbrella-beach', 'text' => '6 km to Dreamland Beach: Dramatic cliffs and golden sands'],
        ['icon' => 'fa-solid fa-golf-ball-tee', 'text' => '6 km to New Kuta Golf Course: Ocean-view golf'],
        ['icon' => 'fa-solid fa-plane-departure', 'text' => '13 km (est. 30 minutes) from Ngurah Rai International Airport'],
        ['icon' => 'fa-solid fa-umbrella-beach', 'text' => '20 minutes to Pandawa Beach: Secluded coastal beauty'],
        ['icon' => 'fa-solid fa-landmark', 'text' => 'Close to Uluwatu Temple: Bali’s legendary cliffside landmark'],
        ['icon' => 'fa-solid fa-martini-glass-citrus', 'text' => 'Near Single Fin: Iconic sunset cocktails overlooking the ocean'],
        ['icon' => 'fa-solid fa-utensils', 'text' => 'Surrounded by fine restaurants & local warungs.'],
        ['icon' => 'fa-solid fa-bag-shopping', 'text' => '10 minutes to shopping & daily essentials.'],
    ];

    $testimonials = [
        ['name' => 'Raisha Shabrina', 'text' => 'Super comfy villa, great vibes, and totally made us feel at home. Loved every moment here!'],
        ['name' => 'Nicole', 'text' => 'Beautiful stay and amazing memory to last a lifetime. The property is so beautiful with traditional Balinese design, spacious bedrooms, peaceful nights, and everything we needed for a birthday trip with friends. Ed helped us feel looked after from start to finish. 10/10 would recommend!'],
        ['name' => 'Afif Rohman', 'text' => 'We stayed at Swarna Mandapa for our honeymoon. The villa was clean, and the private pool made going outside feel optional. Quiet, romantic, and perfect for couples who want to disappear without actually disappearing.'],
        ['name' => 'Nabila Maharani', 'text' => 'Very nice place to stay in Bali, very comfortable and highly recommended.'],
        ['name' => 'N Mwalaa', 'text' => 'Such a lovely place in Uluwatu! The villa feels cozy yet luxurious, very clean, and thoughtfully designed. The pool is a great spot to relax, and the whole place has a peaceful vibe that makes you slow down and enjoy the moment.'],
        ['name' => 'Irma Fitriani', 'text' => 'Great place to stay. Smooth check in, clean rooms, and plenty of space. Everything worked well for our trip.'],
        ['name' => 'Koalarmb Laby', 'text' => 'Amazing place to stay in Uluwatu. The villa is beautiful, spacious, and super clean, with a stunning Balinese design. The pool area is perfect for chilling, and the atmosphere is calm and relaxing.'],
        ['name' => 'Salsa Maulida', 'text' => 'A very comfortable place to stay in Swarna Mandapa. This villa is very spacious with beautiful interior, a relaxing swimming pool, and a location close to the beach. It is highly recommended.'],
    ];

    $footerLinks = [
        'Features' => '#features',
        'Gallery' => route('gallery'),
        'Reviews' => route('reviews'),
        'Contact Us' => route('contact-us'),
    ];
@endphp

<x-layout title="Swarna Mandapa">
    <x-site-header variant="transparent" />

    <main class="overflow-hidden">
        <section id="home" class="relative flex min-h-screen flex-col items-center justify-center text-white pt-[80px] sm:pt-[96px]">
            <video class="absolute inset-0 size-full object-cover brightness-65" autoplay muted loop playsinline aria-label="Swarna Mandapa pool courtyard">
                <source src="{{ $asset('sm.MP4') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-b from-black/15 via-black/5 to-black/20"></div>

            <div class="hidden">
            </div>

            <div class="relative z-10 grid w-full justify-items-center px-5 pb-12 text-center sm:px-12 lg:pb-[72px]" data-reveal>
                <h1 class="font-brand text-[44px] font-medium leading-tight text-[#ffdc7d] sm:text-6xl lg:text-[64px]">Swarna Mandapa</h1>
                <p class="mt-3 max-w-4xl text-lg leading-snug sm:text-2xl">A golden sanctuary where tradition and luxury live in perfect harmony.</p>
                <div class="mt-6 grid w-full max-w-[360px] gap-1 px-4">
                    <x-gold-button href="{{ route('booking.form') }}" class="w-full">Check Availability →</x-gold-button>
                    <a href="{{ route('contact-us') }}" class="font-serif text-base text-white underline-offset-4">or <strong class="underline">Contact Us</strong></a>
                </div>
            </div>
        </section>

        <section class="grid gap-12 bg-white px-5 py-14 sm:px-10 lg:gap-[72px] lg:px-[300px] lg:py-[144px]">
            <h2 class="mx-auto max-w-[1168px] text-center font-serif text-[30px] font-bold italic leading-snug text-[#71562a] sm:text-[40px]" data-reveal>
                Swarna Mandapa, Where Architectural Heritage Meets Contemporary Luxury Living
            </h2>
            <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-20" data-parallax>
                <article class="grid gap-10" data-reveal>
                    <p class="font-serif text-lg leading-snug text-[#71562a] sm:text-2xl lg:text-justify">Swarna Mandapa began life as two distinct buildings: a modest village home and a revered spiritual hall once owned by a Balinese high priest.</p>
                    <div class="overflow-hidden">
                        <img src="{{ $asset('about-heritage.png') }}" alt="Interior courtyard with pool and carved columns" class="h-[420px] w-full object-cover sm:h-[560px] lg:h-[702px]" loading="lazy" data-parallax-item data-parallax-speed="72">
                    </div>
                </article>
                <article class="grid gap-10 lg:mt-[102px]" data-reveal data-reveal-delay="120">
                    <div class="overflow-hidden">
                        <img src="{{ $asset('about-garden.png') }}" alt="Tropical garden beside the villa" class="h-[340px] w-full object-cover sm:h-[466px]" loading="lazy" data-parallax-item data-parallax-speed="-56">
                    </div>
                    <p class="font-serif text-lg leading-snug text-[#71562a] sm:text-2xl lg:text-justify">For 24 years, the priest taught traditional religion here, guiding students in the sacred art of stone carving, a deeply spiritual practice used to express and understand the teachings of Balinese religion.</p>
                </article>
            </div>
        </section>

        <section id="features" class="grid items-center gap-10 border border-[#e4dcc8] bg-[#fefdf9] px-5 py-12 sm:px-10 lg:grid-cols-2 lg:gap-0 lg:px-[300px] lg:py-[72px]">
            <div class="grid gap-9 lg:px-6">
                <x-section-heading title="Our Luxury Features & Amenities" align="left" class="[&_h2]:text-left" />
                <div class="grid gap-9" id="featuresContainer">
                    @foreach ($features as $feature)
                        <x-feature-item :feature="$feature" :delay="$loop->index * 80" />
                    @endforeach
                    <div id="moreFeatures" class="transition-all duration-300" style="display: none; opacity: 0;">
                        @foreach ($moreFeatures as $feature)
                            <x-feature-item :feature="$feature" :delay="$loop->index * 80" />
                        @endforeach
                    </div>
                </div>
                <button onclick="toggleAmenities()" class="w-full rounded-full bg-[#c5a858] px-6 py-3 font-serif font-bold text-white no-underline transition hover:bg-[#d4b868] active:bg-[#b39548]">View More ↓</button>
            </div>
            <div class="lg:p-2.5" data-reveal data-reveal-delay="160">
                <img src="{{ $asset('amenities.png') }}" alt="Coffee service and villa kitchen detail" class="h-[420px] w-full object-cover sm:h-[560px] lg:h-[721px]" loading="lazy">
            </div>
        </section>

        <section id="gallery" class="bg-white px-5 py-14 sm:px-10 lg:px-[300px] lg:py-[108px]">
            <x-section-heading
                title="Signature Gallery"
                subtitle="Explore the refined beauty of Swarna Mandapa through stunning visuals that capture the villa’s unique charm."
                class="mb-9"
            />

            <div class="mx-auto grid max-w-[1296px] gap-3 sm:gap-6 lg:grid-cols-[minmax(0,818px)_minmax(320px,450px)] lg:gap-x-7">
                <div class="grid gap-3 sm:gap-6">
                    <x-image-card :src="$asset('gallery-living.png')" alt="Bright living room with traditional ceiling" class="h-[420px] sm:h-[620px] lg:h-[757px]" />
                    <div class="grid gap-3 sm:grid-cols-2 sm:gap-6">
                        <x-image-card :src="$asset('gallery-room.png')" alt="Guest bedroom" class="h-[260px] sm:h-[365px]" delay="80" />
                        <x-image-card :src="$asset('gallery-door.png')" alt="Carved Balinese door" class="h-[260px] sm:h-[365px]" delay="160" />
                    </div>
                </div>
                <div class="grid gap-3 sm:gap-6">
                    <x-image-card :src="$asset('gallery-kitchen.png')" alt="Warm kitchen detail" class="h-[180px] sm:h-[197px]" delay="100" />
                    <x-image-card :src="$asset('gallery-statue.png')" alt="Carved decorative statue" class="h-[420px] sm:h-[689px]" delay="180" />
                    <x-image-card :src="$asset('gallery-pool.png')" alt="Pool and terrace detail" class="h-[180px] sm:h-[212px]" delay="260" />
                </div>
            </div>

            <a href="{{ route('gallery') }}" class="mx-auto mt-9 block max-w-[1296px] font-serif text-lg font-bold uppercase text-[#71562a] underline transition hover:text-[#c5a858] sm:text-2xl" data-reveal>Discover More →</a>
        </section>

        <section id="suites" class="border border-[#e4dcc8] bg-[#fefdf9] px-5 py-14 sm:px-10 lg:px-[300px] lg:py-[108px]">
            <x-section-heading
                title="Elegant Suites Designed for Rest & Renewal"
                subtitle="Explore the refined beauty of Swarna Mandapa through stunning visuals that capture the villa’s unique charm."
                class="mb-12"
            />
            <div class="grid gap-6 lg:grid-cols-2" data-reveal>
                <div class="relative rounded-2xl overflow-hidden group">
                    <img src="{{ $asset('master-suite.png') }}" alt="Grand master suite" class="h-[260px] w-full object-cover sm:h-[357px]" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-6 text-white">
                        <h3 class="font-serif text-2xl font-bold mb-2">Grand Master Suite</h3>
                        <p class="text-sm leading-4">The Grand Master Suite (150 sqm) is a private sanctuary of pure luxury, featuring a designer Super King bed, elegant ensuite with clawfoot bath, and a private deck overlooking lush greenery, the perfect escape to indulge and unwind.</p>
                    </div>
                </div>
                <div class="relative rounded-2xl overflow-hidden group">
                    <img src="{{ $asset('grand-suite.png') }}" alt="Guest suite with carved details" class="h-[260px] w-full object-cover sm:h-[357px]" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-6 text-white">
                        <h3 class="font-serif text-2xl font-bold mb-2">Master Guest Suite</h3>
                        <p class="text-sm leading-4">The Master Guest Suite (90 sqm) is a refined retreat of comfort and grandeur, featuring a designer Super King bed, elegant décor, and expansive windows that open to a private outdoor area. Rich textures and serene views come together to reflect the home's unmistakable sense of luxury.</p>
                    </div>
                </div>
            </div>
            <x-section-heading
                title="Guest Suites"
                subtitle="Each of the three Guest Suites embodies Swarna Mandapa’s signature sense of scale and style. With super comfy King beds, private ensuite bathrooms, and beautifully crafted details, these suites offer guests an indulgent experience of their own."
                level="h3"
                class="my-12"
            />
            <div class="grid gap-4 md:grid-cols-3">
                <x-image-card :src="$asset('guest-suite-1.png')" alt="Guest suite bed" class="h-[238px]" />
                <x-image-card :src="$asset('guest-suite-2.png')" alt="Guest suite lounge detail" class="h-[238px]" delay="100" />
                <x-image-card :src="$asset('guest-suite-3.png')" alt="Guest suite bathroom detail" class="h-[238px]" delay="200" />
            </div>
        </section>

        <section class="grid gap-9 bg-white px-5 py-14 sm:px-10 lg:px-[300px] lg:pt-[108px]">
            <x-section-heading title="Our Location" />
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3942.5648215336983!2d115.15581127604193!3d-8.826888091226579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd25b00485acd31%3A0xb88b2e45cf7b9df3!2sSwarna%20Mandapa%20-%20Luxury%20Holiday%20Rental%20in%20Uluwatu!5e0!3m2!1sen!2ssg!4v1777117557995!5m2!1sen!2ssg" class="h-[260px] w-full rounded-2xl object-cover sm:h-[367px]" loading="lazy" data-reveal> </iframe>
            <div class="border border-[#e4dcc8] bg-[#fefdf9] p-8 sm:p-10 rounded-2xl">
                <h3 class="mb-9 text-center font-serif text-[32px] font-bold text-[#c5a858] sm:text-4xl">Explore What's Around You</h3>
                <div class="grid gap-8 md:grid-cols-2 md:gap-x-12 md:gap-y-8">
                    @foreach ($nearby as $item)
                        <x-location-item :text="$item['text']" :icon="$item['icon']" :delay="$loop->index * 50" />
                    @endforeach
                </div>
            </div>
        </section>

        <section id="reviews" class="bg-white px-5 py-14 sm:px-10 lg:px-[300px] lg:pb-[144px]">
            <x-section-heading title="What Our Guests Say" class="mb-12" />
            <div class="columns-1 gap-7 md:columns-2 xl:columns-3">
                @foreach ($testimonials as $testimonial)
                    <x-testimonial-card :testimonial="$testimonial" :delay="$loop->index * 60" />
                @endforeach
            </div>
        </section>

        <section class="grid justify-items-center gap-6 border border-[#e4dcc8] bg-[#fefdf9] px-5 py-14 text-center sm:px-10 lg:px-[300px] lg:py-[144px]" data-reveal>
            <h2 class="font-serif text-[32px] font-bold leading-tight text-[#c5a858] sm:text-5xl">
                Ready to Secure Your<br class="hidden sm:block"> Private Luxury Escape in Bali?
            </h2>
            <p class="font-serif text-lg font-bold text-[#71562a] sm:text-2xl">Reserve your preferred dates now!</p>
            <x-gold-button href="{{ route('booking.form') }}" class="w-full sm:w-auto sm:px-12">Check Availability →</x-gold-button>
        </section>

        <x-site-footer :links="$footerLinks" />
    </main>

    <script>
        let isExpanded = false;

        function toggleAmenities() {
            const moreFeatures = document.getElementById('moreFeatures');
            const button = event.target;
            const container = document.getElementById('featuresContainer');

            if (!isExpanded) {
                // Show more features
                moreFeatures.style.display = 'grid';
                moreFeatures.style.gap = '2.25rem'; // gap-9
                
                // Trigger animation
                setTimeout(() => {
                    moreFeatures.style.opacity = '1';
                }, 10);

                button.textContent = 'View Less ↑';
                isExpanded = true;

                // Smooth scroll to the newly revealed items
                setTimeout(() => {
                    moreFeatures.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            } else {
                // Hide more features
                moreFeatures.style.opacity = '0';
                setTimeout(() => {
                    moreFeatures.style.display = 'none';
                }, 300);

                button.textContent = 'View More ↓';
                isExpanded = false;

                // Smooth scroll back to button
                setTimeout(() => {
                    button.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        }
    </script>
</x-layout>
