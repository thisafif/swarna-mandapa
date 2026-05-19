@php
    $asset = fn (string $name) => asset("assets/swarna/{$name}");

    $footerLinks = [
        'Home' => url('/'),
        'Features' => url('/#features'),
        'Gallery' => route('gallery'),
        'Reviews' => route('reviews'),
        'Contact Us' => route('contact-us'),
    ];
@endphp

<x-layout title="Contact Us | Swarna Mandapa">
    <x-site-header />

    <main class="overflow-hidden bg-white pt-[96px]">
        <section class="px-5 pb-16 pt-12 sm:px-10 sm:pb-20 sm:pt-16 lg:px-20 lg:pb-[83px] lg:pt-[82px]">
            <div class="mx-auto grid max-w-[1280px] justify-items-center">
                <div class="mb-8 grid max-w-[762px] justify-items-center gap-3 text-center sm:mb-12">
                    <div class="flex items-center justify-center gap-3 text-[#b8892e]/70" aria-hidden="true">
                        <span class="h-px w-10 bg-current"></span>
                        <span class="font-serif text-xs leading-none tracking-[0.14em]">✦</span>
                        <span class="h-px w-10 bg-current"></span>
                    </div>
                    <p class="font-serif text-sm uppercase tracking-[0.28em] text-[#b8892e] sm:text-[18px]">Personal Concierge</p>
                    <h1 class="font-serif text-[42px] font-normal leading-tight text-[#1e1408] sm:text-[54px]">
                        Send Us a <em class="font-normal text-[#9e6e42]">Message</em>
                    </h1>
                    <p class="max-w-[680px] font-serif text-base leading-[1.8] text-[#6a5438] sm:text-[17px]">
                        Whether planning a honeymoon, a family retreat, or a private celebration, we're here to make every detail perfect.
                    </p>
                </div>

                <div class="grid w-full max-w-[856px] overflow-hidden rounded-[28px] border border-[#b8892e]/10 bg-[#fdfaf4] shadow-[0_7px_21px_rgba(42,26,8,0.08),0_28px_70px_rgba(42,26,8,0.14)] lg:grid-cols-[minmax(0,1fr)_367px]" data-reveal>
                    <section class="grid gap-4 px-6 py-8 sm:px-10 sm:py-10 lg:px-[42px] lg:pb-[45px] lg:pt-[42px]" aria-labelledby="concierge-form-title">
                        <div class="grid gap-1">
                            <p class="font-serif text-[10px] uppercase tracking-[0.32em] text-[#b8892e]">Your Details</p>
                            <h2 id="concierge-form-title" class="font-serif text-[24px] leading-tight text-[#1e1408]">
                                We'd love to<br>
                                <em class="font-normal text-[#9e6e42]">hear from you</em>
                            </h2>
                        </div>

                        <form action="mailto:reservations@swarnamandapa.com" method="post" enctype="text/plain" class="grid gap-4" data-mailto-form>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label class="grid gap-1.5">
                                    <span class="font-serif text-[10px] font-bold uppercase tracking-[0.1em] text-[#3a2a14]">First Name <span class="text-[#b8892e]">*</span></span>
                                    <input required name="first_name" autocomplete="given-name" placeholder="First name" class="min-h-11 rounded-xl border border-[#e4dac8] bg-[#fefcf6] px-4 py-3 font-serif text-sm text-[#3a2a14] outline-none transition placeholder:text-[#b8a888] focus:border-[#b8892e] focus:ring-2 focus:ring-[#b8892e]/20">
                                </label>
                                <label class="grid gap-1.5">
                                    <span class="font-serif text-[10px] font-bold uppercase tracking-[0.1em] text-[#3a2a14]">Last Name <span class="text-[#b8892e]">*</span></span>
                                    <input required name="last_name" autocomplete="family-name" placeholder="Last name" class="min-h-11 rounded-xl border border-[#e4dac8] bg-[#fefcf6] px-4 py-3 font-serif text-sm text-[#3a2a14] outline-none transition placeholder:text-[#b8a888] focus:border-[#b8892e] focus:ring-2 focus:ring-[#b8892e]/20">
                                </label>
                            </div>

                            <label class="grid gap-1.5">
                                <span class="font-serif text-[10px] font-bold uppercase tracking-[0.1em] text-[#3a2a14]">Email Address <span class="text-[#b8892e]">*</span></span>
                                <input required type="email" name="email" autocomplete="email" placeholder="your@email.com" class="min-h-11 rounded-xl border border-[#e4dac8] bg-[#fefcf6] px-4 py-3 font-serif text-sm text-[#3a2a14] outline-none transition placeholder:text-[#b8a888] focus:border-[#b8892e] focus:ring-2 focus:ring-[#b8892e]/20">
                            </label>

                            <label class="grid gap-1.5">
                                <span class="font-serif text-[10px] font-bold uppercase tracking-[0.1em] text-[#3a2a14]">Phone Number</span>
                                <input type="tel" name="phone" autocomplete="tel" placeholder="+62 xxx xxxx xxxx" class="min-h-11 rounded-xl border border-[#e4dac8] bg-[#fefcf6] px-4 py-3 font-serif text-sm text-[#3a2a14] outline-none transition placeholder:text-[#b8a888] focus:border-[#b8892e] focus:ring-2 focus:ring-[#b8892e]/20">
                            </label>

                            <label class="grid gap-1.5">
                                <span class="font-serif text-[10px] font-bold uppercase tracking-[0.1em] text-[#3a2a14]">Your Message <span class="text-[#b8892e]">*</span></span>
                                <textarea required name="message" rows="5" placeholder="Tell us how we can help you plan your stay..." class="min-h-[102px] resize-y rounded-xl border border-[#e4dac8] bg-[#fefcf6] px-4 py-3 font-serif text-sm text-[#3a2a14] outline-none transition placeholder:text-[#b8a888] focus:border-[#b8892e] focus:ring-2 focus:ring-[#b8892e]/20"></textarea>
                            </label>

                            <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-[14px] bg-[#b8892e] px-6 py-3 font-serif text-[11px] font-bold uppercase tracking-[0.22em] text-[#fdfaf4] shadow-[0_4px_8px_rgba(184,137,46,0.3)] transition hover:bg-[#9e6e42] focus:outline-none focus:ring-2 focus:ring-[#b8892e] focus:ring-offset-2">
                                <span aria-hidden="true">✈</span>
                                Send Message
                            </button>
                        </form>

                        <p class="flex items-center justify-center gap-2 text-center font-sans text-[11px] font-light tracking-[0.02em] text-[#b8a888]">
                            <span class="text-[#b8892e]" aria-hidden="true">ⓘ</span>
                            Our concierge team will respond within 24 hours.
                        </p>
                    </section>

                    <aside class="relative min-h-[420px] overflow-hidden lg:min-h-[605px]" aria-label="Swarna Mandapa promise">
                        <img src="{{ $asset('about-concierge.png') }}" alt="Golden Swarna Mandapa lounge with carved wall details" class="absolute inset-0 size-full object-cover object-center lg:scale-[1.18] lg:object-[48%_55%]">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#2a1a08]/85 via-[#2a1a08]/15 to-transparent"></div>
                        <div class="absolute inset-x-6 bottom-7 grid gap-2 text-[#fefaf3]/90">
                            <blockquote class="font-serif text-base italic leading-[1.6]">
                                "Every request, no matter how small, is an opportunity to exceed expectations."
                            </blockquote>
                            <p class="font-serif text-[10px] uppercase tracking-[0.22em] text-[#c4a84a]/80">The Swarna Mandapa Promise</p>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <x-site-footer :links="$footerLinks" />
    </main>
</x-layout>
