
@php
    use App\Models\GuestReview;

    $testimonials = GuestReview::approved()->latest()->get();
    $featured     = $testimonials->first();

    $footerLinks = [
        'Home'       => url('/'),
        'Features'   => url('/#features'),
        'Gallery'    => route('gallery'),
        'Reviews'    => route('reviews'),
        'Contact Us' => route('contact-us'),
    ];
@endphp

<x-layout title="Reviews | Swarna Mandapa">
    <x-site-header />

    <main class="overflow-hidden bg-white pt-[36px] sm:pt-[44px]">
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

                    {{-- Featured quote sidebar --}}
                    <aside class="relative isolate grid min-h-[430px] overflow-hidden bg-gradient-to-br from-[#71562a] to-[#9c7b3a] px-7 py-9 text-[#fefaf3]/90 sm:px-10 sm:py-12 lg:min-h-[595px]">
                        <span class="pointer-events-none absolute -left-5 top-0 -z-10 font-serif text-[220px] font-bold leading-none text-[#c4984a]/10 sm:text-[283px]" aria-hidden="true">"</span>
                        <div class="grid content-start gap-5">
                            <div class="flex gap-1 text-[#c4984a]" aria-label="5 star rating">
                                @for ($i = 0; $i < 5; $i++)
                                    <span aria-hidden="true">★</span>
                                @endfor
                            </div>
                            @if ($featured)
                                <blockquote class="font-serif text-base italic leading-[1.62] sm:text-[17px]">
                                    "{{ $featured->review }}"
                                </blockquote>
                                <div class="flex items-center gap-3 pt-2">
                                    <strong class="font-serif text-sm text-[#fefaf3]/80">- {{ $featured->full_name }}</strong>
                                </div>
                            @else
                                <blockquote class="font-serif text-base italic leading-[1.62] sm:text-[17px]">
                                    "Be the first to share your experience at Swarna Mandapa."
                                </blockquote>
                            @endif
                        </div>
                    </aside>

                    {{-- Review form --}}
                    <section class="grid content-center gap-7 bg-[#fdfaf5] px-6 py-9 sm:px-10 sm:py-12 lg:px-[42px] lg:py-[71px]" aria-labelledby="review-form-title">
                        <div class="grid gap-2">
                            <p class="font-serif text-xs uppercase tracking-[0.24em] text-[#c4984a]">Leave a Review</p>
                            <h2 id="review-form-title" class="font-serif text-2xl font-medium text-[#1c1208]">
                                How was your <em class="font-normal text-[#9e6e42]">stay?</em>
                            </h2>
                        </div>

                        <form id="review-form" class="grid gap-4" novalidate>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label class="grid gap-1.5">
                                    <span class="font-serif text-[10px] font-bold uppercase tracking-[0.12em] text-[#3a2a18]">First Name</span>
                                    <input required name="first_name" autocomplete="given-name" placeholder="First name"
                                           class="min-h-11 rounded-2xl border border-[#e8e0d0] bg-[#f6f2ec] px-4 py-3 font-serif text-sm text-[#3a2a18] outline-none transition placeholder:text-[#b8a898] focus:border-[#c4984a] focus:ring-2 focus:ring-[#c4984a]/20">
                                </label>
                                <label class="grid gap-1.5">
                                    <span class="font-serif text-[10px] font-bold uppercase tracking-[0.12em] text-[#3a2a18]">Last Name</span>
                                    <input required name="last_name" autocomplete="family-name" placeholder="Last name"
                                           class="min-h-11 rounded-2xl border border-[#e8e0d0] bg-[#f6f2ec] px-4 py-3 font-serif text-sm text-[#3a2a18] outline-none transition placeholder:text-[#b8a898] focus:border-[#c4984a] focus:ring-2 focus:ring-[#c4984a]/20">
                                </label>
                            </div>

                            <label class="grid gap-1.5">
                                <span class="font-serif text-[10px] font-bold uppercase tracking-[0.12em] text-[#3a2a18]">Email Address</span>
                                <input required type="email" name="email" autocomplete="email" placeholder="your@email.com"
                                       class="min-h-11 rounded-2xl border border-[#e8e0d0] bg-[#f6f2ec] px-4 py-3 font-serif text-sm text-[#3a2a18] outline-none transition placeholder:text-[#b8a898] focus:border-[#c4984a] focus:ring-2 focus:ring-[#c4984a]/20">
                            </label>

                            <label class="grid gap-1.5">
                                <span class="font-serif text-[10px] font-bold uppercase tracking-[0.12em] text-[#3a2a18]">Your Review</span>
                                <textarea required name="review" rows="5" placeholder="Describe your experience - the atmosphere, service, memorable moments..."
                                          class="min-h-[106px] resize-y rounded-2xl border border-[#e8e0d0] bg-[#f6f2ec] px-4 py-3 font-serif text-sm text-[#3a2a18] outline-none transition placeholder:text-[#b8a898] focus:border-[#c4984a] focus:ring-2 focus:ring-[#c4984a]/20"></textarea>
                            </label>

                            <input type="hidden" name="rating" value="5">

                            <div id="review-alert" class="hidden rounded-2xl px-4 py-3 font-serif text-sm" role="alert"></div>

                            <button type="submit" id="review-submit-btn"
                                    class="mt-4 inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-[#aa7d00] px-7 py-3.5 font-serif text-[10px] font-bold uppercase tracking-[0.16em] text-[#fdfaf5] shadow-[0_1px_2px_rgba(42,26,8,0.05),0_4px_8px_rgba(42,26,8,0.07)] transition hover:bg-[#9e6e42] focus:outline-none focus:ring-2 focus:ring-[#c4984a] focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed">
                                <span aria-hidden="true" id="submit-icon">☆</span>
                                <span id="submit-label">Submit Review</span>
                            </button>
                        </form>
                    </section>
                </section>
            </div>
        </section>

        {{-- Guest reviews grid --}}
        <section class="border border-[#e4dcc8] bg-[#fefdf9] px-5 py-14 sm:px-10 lg:px-[300px] lg:py-[96px]">
            <x-section-heading title="More Guest Voices" subtitle="A few kind words from guests who have experienced Swarna Mandapa's calm, space, and thoughtful details." class="mb-12" />
            @if ($testimonials->isNotEmpty())
                <div class="columns-1 gap-7 md:columns-2 xl:columns-3">
                    @foreach ($testimonials as $testimonial)
                        <x-testimonial-card
                            :testimonial="['name' => $testimonial->full_name, 'text' => $testimonial->review]"
                            :delay="$loop->index * 60" />
                    @endforeach
                </div>
            @else
                <p class="text-center font-serif text-base text-[#6a5540]">No reviews yet. Be the first to share your experience!</p>
            @endif
        </section>

        <section class="grid justify-items-center gap-6 bg-white px-5 py-14 text-center sm:px-10 lg:px-[300px] lg:py-[96px]" data-reveal>
            <h2 class="font-serif text-[32px] font-bold leading-tight text-[#c5a858] sm:text-5xl">
                Ready to Create Your<br class="hidden sm:block"> Own Swarna Mandapa Story?
            </h2>
            <p class="font-serif text-lg font-bold text-[#71562a] sm:text-2xl">Reserve your preferred dates with our concierge team.</p>
            <x-gold-button href="{{ route('booking.form') }}" class="w-full sm:w-auto sm:px-12">Check Availability →</x-gold-button>
        </section>

        <x-site-footer :links="$footerLinks" />
    </main>

    <script>
    document.getElementById('review-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const btn      = document.getElementById('review-submit-btn');
        const label    = document.getElementById('submit-label');
        const icon     = document.getElementById('submit-icon');
        const alertBox = document.getElementById('review-alert');

        btn.disabled       = true;
        label.textContent  = 'Sending…';
        icon.textContent   = '⏳';
        alertBox.className = 'hidden';

        const payload = {
            first_name: this.first_name.value.trim(),
            last_name:  this.last_name.value.trim(),
            email:      this.email.value.trim(),
            review:     this.review.value.trim(),
            rating:     this.rating.value,
        };

        try {
            const res = await fetch('{{ route("reviews.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            if (res.ok) {
                alertBox.textContent = '✨ Thank you! Your review has been submitted and is awaiting approval.';
                alertBox.className   = 'rounded-2xl px-4 py-3 font-serif text-sm bg-green-50 text-green-800 border border-green-200';
                this.reset();
            } else {
                const err = await res.json().catch(() => ({}));
                const msg = err?.message ?? 'Please check your input and try again.';
                alertBox.textContent = `✗ ${msg}`;
                alertBox.className   = 'rounded-2xl px-4 py-3 font-serif text-sm bg-red-50 text-red-700 border border-red-200';
            }
        } catch {
            alertBox.textContent = '✗ Connection error. Please try again.';
            alertBox.className   = 'rounded-2xl px-4 py-3 font-serif text-sm bg-red-50 text-red-700 border border-red-200';
        } finally {
            btn.disabled      = false;
            label.textContent = 'Submit Review';
            icon.textContent  = '☆';
        }
    });
    </script>
</x-layout>