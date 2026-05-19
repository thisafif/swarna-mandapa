@php
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

    $featured = collect($testimonials)->firstWhere('name', 'Koalarmb Laby') ?? $testimonials[0];

    $initials = fn (string $name) => collect(explode(' ', $name))
        ->filter()
        ->take(2)
        ->map(fn ($part) => str($part)->substr(0, 1)->upper())
        ->implode('');

    $footerLinks = [
        'Home' => url('/'),
        'Features' => url('/#features'),
        'Gallery' => route('gallery'),
        'Reviews' => route('reviews'),
        'Contact Us' => route('contact-us'),
    ];
@endphp

<x-layout title="Reviews | Swarna Mandapa">
    <x-site-header />

    <main class="overflow-hidden bg-white pt-[80px] sm:pt-[96px]">
        <section class="px-5 pb-14 pt-12 sm:px-10 sm:pb-20 sm:pt-16 lg:px-20 lg:pb-[114px] lg:pt-[114px]">
            <div class="mx-auto grid w-full max-w-[1044px] gap-10 lg:gap-[50px]">
                <div class="mx-auto grid max-w-[620px] justify-items-center gap-3 text-center" data-reveal>
                    <div class="flex items-center justify-center gap-5 text-[#c4984a]/70" aria-hidden="true">
                        <span class="h-px w-12 bg-current"></span>
                        <span class="font-serif text-sm leading-none tracking-[0.2em]">✦</span>
                        <span class="h-px w-12 bg-current"></span>
                    </div>
                    <p class="font-serif text-sm uppercase tracking-[0.22em] text-[#c4984a] sm:text-[18px]">Guest Voices</p>
                    <h1 class="text-wrap font-serif text-[38px] font-medium leading-tight text-[#c5a858] sm:text-[41px]">
                        Share Your <em class="font-normal text-[#9e6e42]">Experience</em>
                    </h1>
                    <p class="max-w-[440px] font-serif text-base leading-relaxed text-[#6a5540] sm:text-[18px]">
                        Your story inspires future guests and helps us grow. We'd be honoured to hear about your stay.
                    </p>
                </div>

                <section class="grid overflow-hidden rounded-[32px] border border-[#c4984a]/10 bg-[#fdfaf5] shadow-[0_7px_21px_rgba(42,26,8,0.08),0_28px_70px_rgba(42,26,8,0.13)] lg:grid-cols-[371px_minmax(0,1fr)] lg:rounded-[35px]" data-reveal aria-label="Share a review">
                    <aside class="relative isolate grid min-h-[430px] overflow-hidden bg-gradient-to-br from-[#71562a] to-[#9c7b3a] px-7 py-9 text-[#fefaf3]/90 sm:px-10 sm:py-12 lg:min-h-[595px]">
                        <span class="pointer-events-none absolute -left-5 top-0 -z-10 font-serif text-[220px] font-bold leading-none text-[#c4984a]/10 sm:text-[283px]" aria-hidden="true">“</span>
                        <div class="grid content-start gap-5">
                            <div class="flex gap-1 text-[#c4984a]" aria-label="5 star rating">
                                @for ($i = 0; $i < 5; $i++)
                                    <span aria-hidden="true">★</span>
                                @endfor
                            </div>
                            <blockquote class="font-serif text-base italic leading-[1.62] sm:text-[17px]">
                                “{{ $featured['text'] }}”
                            </blockquote>
                            <div class="flex items-center gap-3 pt-2">
                                <span class="grid size-11 place-items-center rounded-full bg-gradient-to-br from-[#c4984a] to-[#9e6e42] font-serif text-sm font-medium text-white">{{ $initials($featured['name']) }}</span>
                                <strong class="font-serif text-sm text-[#fefaf3]/80">- {{ $featured['name'] }}</strong>
                            </div>
                        </div>
                    </aside>

                    <section class="grid content-center gap-7 bg-[#fdfaf5] px-6 py-9 sm:px-10 sm:py-12 lg:px-[42px] lg:py-[71px]" aria-labelledby="review-form-title">
                        <div class="grid gap-2">
                            <p class="font-serif text-xs uppercase tracking-[0.24em] text-[#c4984a]">Leave a Review</p>
                            <h2 id="review-form-title" class="font-serif text-2xl font-medium text-[#1c1208]">
                                How was your <em class="font-normal text-[#9e6e42]">stay?</em>
                            </h2>
                        </div>

                        <form action="mailto:reservations@swarnamandapa.com" method="post" enctype="text/plain" class="grid gap-4" data-review-mailto-form>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label class="grid gap-1.5">
                                    <span class="font-serif text-[10px] font-bold uppercase tracking-[0.12em] text-[#3a2a18]">First Name</span>
                                    <input required name="first_name" autocomplete="given-name" placeholder="First name" class="min-h-11 rounded-2xl border border-[#e8e0d0] bg-[#f6f2ec] px-4 py-3 font-serif text-sm text-[#3a2a18] outline-none transition placeholder:text-[#b8a898] focus:border-[#c4984a] focus:ring-2 focus:ring-[#c4984a]/20">
                                </label>
                                <label class="grid gap-1.5">
                                    <span class="font-serif text-[10px] font-bold uppercase tracking-[0.12em] text-[#3a2a18]">Last Name</span>
                                    <input required name="last_name" autocomplete="family-name" placeholder="Last name" class="min-h-11 rounded-2xl border border-[#e8e0d0] bg-[#f6f2ec] px-4 py-3 font-serif text-sm text-[#3a2a18] outline-none transition placeholder:text-[#b8a898] focus:border-[#c4984a] focus:ring-2 focus:ring-[#c4984a]/20">
                                </label>
                            </div>

                            <label class="grid gap-1.5">
                                <span class="font-serif text-[10px] font-bold uppercase tracking-[0.12em] text-[#3a2a18]">Email Address</span>
                                <input required type="email" name="email" autocomplete="email" placeholder="your@email.com" class="min-h-11 rounded-2xl border border-[#e8e0d0] bg-[#f6f2ec] px-4 py-3 font-serif text-sm text-[#3a2a18] outline-none transition placeholder:text-[#b8a898] focus:border-[#c4984a] focus:ring-2 focus:ring-[#c4984a]/20">
                            </label>

                            <label class="grid gap-1.5">
                                <span class="font-serif text-[10px] font-bold uppercase tracking-[0.12em] text-[#3a2a18]">Your Review</span>
                                <textarea required name="review" rows="5" placeholder="Describe your experience - the atmosphere, service, memorable moments..." class="min-h-[106px] resize-y rounded-2xl border border-[#e8e0d0] bg-[#f6f2ec] px-4 py-3 font-serif text-sm text-[#3a2a18] outline-none transition placeholder:text-[#b8a898] focus:border-[#c4984a] focus:ring-2 focus:ring-[#c4984a]/20"></textarea>
                            </label>

                            <input type="hidden" name="rating" value="5">

                            <button type="submit" class="mt-4 inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-[#aa7d00] px-7 py-3.5 font-serif text-[10px] font-bold uppercase tracking-[0.16em] text-[#fdfaf5] shadow-[0_1px_2px_rgba(42,26,8,0.05),0_4px_8px_rgba(42,26,8,0.07)] transition hover:bg-[#9e6e42] focus:outline-none focus:ring-2 focus:ring-[#c4984a] focus:ring-offset-2">
                                <span aria-hidden="true">☆</span>
                                Submit Review
                            </button>
                        </form>
                    </section>
                </section>
            </div>
        </section>

        <section class="border border-[#e4dcc8] bg-[#fefdf9] px-5 py-14 sm:px-10 lg:px-[300px] lg:py-[96px]">
            <x-section-heading title="More Guest Voices" subtitle="A few kind words from guests who have experienced Swarna Mandapa's calm, space, and thoughtful details." class="mb-12" />
            <div class="columns-1 gap-7 md:columns-2 xl:columns-3">
                @foreach ($testimonials as $testimonial)
                    <x-testimonial-card :testimonial="$testimonial" :delay="$loop->index * 60" />
                @endforeach
            </div>
        </section>

        <section class="grid justify-items-center gap-6 bg-white px-5 py-14 text-center sm:px-10 lg:px-[300px] lg:py-[96px]" data-reveal>
            <h2 class="font-serif text-[32px] font-bold leading-tight text-[#c5a858] sm:text-5xl">
                Ready to Create Your<br class="hidden sm:block"> Own Swarna Mandapa Story?
            </h2>
            <p class="font-serif text-lg font-bold text-[#71562a] sm:text-2xl">Reserve your preferred dates with our concierge team.</p>
            <x-gold-button href="{{ route('contact-us') }}" class="w-full sm:w-auto sm:px-12">Check Availability →</x-gold-button>
        </section>

        <x-site-footer :links="$footerLinks" />
    </main>
</x-layout>
