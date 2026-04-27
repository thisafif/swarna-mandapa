@props([
    'variant' => 'solid',
])

@php
    $isTransparent = $variant === 'transparent';
    $headerAttributes = $isTransparent ? 'data-scroll-header' : '';
    $headerClass = $isTransparent
        ? 'fixed left-0 right-0 top-0 z-50 flex min-h-[80px] w-full items-center justify-center bg-transparent px-5 py-4 transition duration-300 sm:min-h-[96px] sm:px-10 sm:justify-between lg:px-[72px]'
        : 'fixed left-0 right-0 top-0 z-50 flex min-h-[80px] w-full items-center justify-center bg-white/90 px-5 py-4 shadow-sm backdrop-blur sm:min-h-[96px] sm:px-10 sm:justify-between lg:px-[72px]';
    $toggleClass = $isTransparent
        ? 'absolute left-5 inline-flex h-11 w-12 flex-col justify-center gap-1.5 rounded-3xl px-3 text-white transition duration-300 sm:static sm:w-12 lg:w-[72px] lg:px-6'
        : 'absolute left-5 inline-flex h-11 w-12 flex-col justify-center gap-1.5 rounded-3xl px-3 text-[#71562a] transition duration-300 sm:static sm:w-12 lg:w-[72px] lg:px-6';
    $ctaClass = $isTransparent
        ? 'hidden rounded bg-white px-3 py-2 font-serif font-bold text-[#c5a858] no-underline transition duration-300 hover:bg-[#ffdc7d] sm:inline-flex'
        : 'hidden rounded bg-[#c5a858] px-3 py-2 font-serif font-bold text-white no-underline transition hover:bg-[#b8892e] sm:inline-flex';
    $navLinks = [
        'Home' => url('/'),
        'Features' => url('/#features'),
        'Gallery' => route('gallery'),
        'Reviews' => route('reviews'),
        'Contact Us' => route('contact-us'),
    ];
@endphp

<header {!! $headerAttributes !!} class="{{ $headerClass }}" aria-label="Primary navigation">
    <button data-scroll-menu data-nav-toggle class="{{ $toggleClass }}" type="button" aria-label="Open navigation" aria-expanded="false">
        <span class="h-0.5 w-6 bg-current"></span>
        <span class="h-0.5 w-6 bg-current"></span>
    </button>
    <x-brand :variant="$isTransparent ? 'light' : 'dark'" href="{{ url('/') }}" />
    <a data-scroll-cta href="{{ route('booking.form') }}" class="{{ $ctaClass }}">Book Now</a>
</header>

<div data-nav-panel class="fixed inset-0 z-[60] hidden" aria-hidden="true">
    <button data-nav-backdrop class="absolute inset-0 bg-black/45 opacity-0 transition-opacity duration-300" type="button" aria-label="Close navigation"></button>
    <aside data-nav-drawer class="relative grid h-full w-[min(88vw,380px)] -translate-x-full content-between bg-[#fefdf9] px-6 py-7 text-[#71562a] shadow-2xl transition-transform duration-300 sm:px-8" role="dialog" aria-modal="true" aria-label="Navigation menu" tabindex="-1">
        <div class="grid gap-10">
            <div class="flex items-center justify-between gap-4">
                <x-brand href="{{ url('/') }}" />
                <button data-nav-close class="grid size-11 place-items-center rounded-full border border-[#e4dcc8] text-[#71562a] transition hover:border-[#c5a858] hover:text-[#b8892e]" type="button" aria-label="Close navigation">
                    <i class="fa-solid fa-xmark text-xl" aria-hidden="true"></i>
                </button>
            </div>

            <nav class="grid gap-1" aria-label="Main navigation">
                @foreach ($navLinks as $label => $href)
                    <a href="{{ $href }}" class="group flex items-center justify-between border-b border-[#e4dcc8] py-4 font-serif text-2xl text-[#71562a] no-underline transition hover:text-[#b8892e]" data-nav-link>
                        <span>{{ $label }}</span>
                        <i class="fa-solid fa-arrow-right text-sm opacity-40 transition group-hover:translate-x-1 group-hover:opacity-100" aria-hidden="true"></i>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="grid gap-5">
            <a href="{{ route('booking.form') }}" class="inline-flex min-h-12 items-center justify-center rounded bg-[#c5a858] px-5 py-3 font-serif font-bold text-white no-underline transition hover:bg-[#b8892e]" data-nav-link>Book Now</a>
            <p class="font-serif text-sm leading-relaxed text-[#71562a]/75">A golden sanctuary where tradition and luxury live in perfect harmony.</p>
        </div>
    </aside>
</div>
