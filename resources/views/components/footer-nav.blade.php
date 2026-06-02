@props(['links' => []])

<nav class="grid gap-4 text-left text-white lg:text-right" aria-label="Footer navigation">
    <h2 class="font-serif text-2xl font-bold">Navigation</h2>
    @foreach ($links as $label => $href)
        <a href="{{ $href }}" class="font-serif text-base !text-white !no-underline transition hover:!text-[#ffdc7d]">{{ $label }}</a>
    @endforeach
</nav>
