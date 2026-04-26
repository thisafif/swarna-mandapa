# Swarna Mandapa Design System

This guide documents the current visual and interaction system for the Swarna Mandapa Laravel/Blade site. Use it when creating new pages, sections, and components so the experience stays consistent with the existing homepage and About Us page.

## Brand Direction

Swarna Mandapa should feel like a private Balinese sanctuary: warm, heritage-rich, refined, spacious, and quietly luxurious. The UI should support the property photography and architectural details rather than compete with them.

Prefer:

- Warm whites, golds, clay browns, and deep carved-wood tones.
- Serif-led typography with restrained sans-serif support.
- Large photography, calm spacing, and clear editorial hierarchy.
- Subtle motion: reveal-on-scroll and gentle parallax only.
- Direct booking/contact CTAs with polished but simple styling.

Avoid:

- Cool corporate palettes, stark black-and-white layouts, or neon accents.
- Overly playful shapes, loud gradients, or decorative clutter.
- Marketing-heavy cards nested inside other cards.
- Dense UI that makes the villa feel transactional.

## Technology

- Framework: Laravel Blade.
- Styling: Tailwind CSS v4 via `resources/css/app.css`.
- JavaScript: `resources/js/app.js`.
- Assets: local files in `public/assets/swarna`.
- Fonts: loaded through Bunny Fonts in `resources/views/components/layout.blade.php`.

Use Blade components for repeated UI:

- `x-layout`
- `x-brand`
- `x-gold-button`
- `x-section-heading`
- `x-image-card`
- `x-feature-item`
- `x-location-item`
- `x-testimonial-card`
- `x-footer-nav`

## Color Tokens

The project currently uses inline Tailwind arbitrary colors. Keep using these values unless the CSS is refactored into formal theme tokens.

| Token | Hex | Usage |
| --- | --- | --- |
| Gold Primary | `#c5a858` | Primary headings, icons, main CTAs, logo text tone |
| Gold Deep | `#b8892e` | About Us form CTA, eyebrow text, active gold states |
| Gold Hover | `#d4b868` | Softer CTA hover on light backgrounds |
| Gold Bright | `#ffdc7d` | Hover links on dark/footer backgrounds |
| Brown Text | `#71562a` | Main body copy on light backgrounds |
| Brown Deep | `#3a2a14` | Form labels, high-emphasis text |
| Brown Ink | `#1e1408` | About Us hero heading primary text |
| Brown Accent | `#9e6e42` | Italic heading accents |
| Footer Brown | `#775c31` | Footer background |
| Cream Section | `#fefdf9` | Alternating section backgrounds and cards |
| Linen Input | `#fefcf6` | Form inputs |
| Warm Card | `#fdfaf4` | About Us form panel |
| Border Warm | `#e4dcc8` | Homepage borders |
| Border Form | `#e4dac8` | Form field borders |
| Muted Placeholder | `#b8a888` | Input placeholder and helper copy |
| Dark Overlay | `#2a1a08` | Image gradient overlays |

Common pairings:

- Light page: `bg-white text-[#71562a]`
- Cream band: `bg-[#fefdf9] border border-[#e4dcc8]`
- Footer: `bg-[#775c31] text-white`
- Overlay card text: `text-white` or `text-[#fefaf3]/90`

## Typography

Configured in `resources/css/app.css`:

- Sans: `PT Sans`, `ui-sans-serif`, `system-ui`, `sans-serif`
- Serif: `PT Serif`, `Georgia`, `serif`
- Brand: `Optima`, `PT Serif`, `Georgia`, `serif`

Usage:

- Brand logo/hero name: `font-brand`
- Main headings: `font-serif`
- Body copy: `font-sans` by default, with `font-serif` for editorial villa copy.
- Form labels: serif, bold, uppercase, increased letter spacing.
- Eyebrows: serif, uppercase, wide tracking.
- Italic emphasis: use serif italic in headings, especially for warm accent phrases.

Current scales:

- Homepage hero title: `text-[44px] sm:text-6xl lg:text-[64px]`
- Section heading: `text-[32px] sm:text-5xl`, bold, `leading-[1.25]`
- Secondary section heading: `text-3xl sm:text-4xl`
- Editorial intro heading: `text-[30px] sm:text-[40px]`, italic/bold
- About Us title: `text-[42px] sm:text-[54px]`, normal weight with italic accent
- Body large serif: `text-lg sm:text-2xl`
- Body default: `text-base`
- Form labels: `text-[10px]`, uppercase, `tracking-[0.1em]`

Do not use negative letter spacing. Keep text readable and allow wrapping on mobile.

## Layout

The site uses full-width sections with constrained inner content.

Page shell:

- Body: `bg-white font-sans text-[#71562a] antialiased`
- Main: `overflow-hidden`
- Standard mobile horizontal padding: `px-5`
- Tablet padding: `sm:px-10`
- Large homepage content padding: `lg:px-[300px]`
- Wide utility/header padding: `lg:px-[72px]`

Section spacing:

- Homepage major sections: `py-14 sm:px-10 lg:py-[144px]`
- About Us intro: `pt-12 pb-16 sm:pt-16 sm:pb-20 lg:pt-[82px] lg:pb-[83px]`
- Section gaps: usually `gap-9`, `gap-12`, or `lg:gap-[72px]`

Responsive patterns:

- Single-column first on mobile.
- Use `lg:grid-cols-2` for paired editorial/image sections.
- Use fixed visual heights for image rhythm, with responsive adjustments.
- Use `max-w-[1296px]` for gallery width and `max-w-[856px]` for About Us form card.

## Header

There are two header states.

Static light page header:

- Used on About Us.
- `fixed left-0 right-0 top-0 z-50`
- `min-h-[80px] sm:min-h-[96px]`
- `bg-white/90 shadow-sm backdrop-blur`
- Centered brand, menu/back control on the left, CTA on the right.

Homepage scroll header:

- Starts transparent over the hero.
- After scroll, JavaScript toggles:
  - `bg-white/90`
  - `shadow-sm`
  - `backdrop-blur`
  - menu color from `text-white` to `text-[#71562a]`
  - CTA from white/gold to gold/white

Implementation hook:

```html
<header data-scroll-header>
    <button data-scroll-menu>...</button>
    <a data-scroll-cta>Book Now</a>
</header>
```

Keep header transitions at `duration-300`.

## Buttons And Links

Primary gold button:

- Use `x-gold-button`.
- Shape: `rounded-[32px]`
- Minimum height: `min-h-[62px]`
- Gradient: `from-[#9a7b3e] to-[#c5a858]`
- Typography: `font-serif text-xl sm:text-2xl font-bold`
- Interaction: `hover:brightness-110`, focus ring in gold.

Compact gold button:

- Used for header and forms.
- Background: `#c5a858` or `#b8892e`
- Text: white or warm cream.
- Radius: `rounded`, `rounded-[14px]`, or `rounded-full` depending on context.

Text links:

- Footer/dark backgrounds: white, hover `#ffdc7d`.
- Light backgrounds: brown, hover gold.
- Booking/contact links should route to `route('contact-us')` unless they are direct phone/mail links.

## Forms

Current form pattern lives in `resources/views/contact-us.blade.php`.

Field style:

- Wrapper: `grid gap-1.5`
- Label: `font-serif text-[10px] font-bold uppercase tracking-[0.1em] text-[#3a2a14]`
- Required mark: `text-[#b8892e]`
- Input background: `bg-[#fefcf6]`
- Border: `border border-[#e4dac8]`
- Radius: `rounded-xl`
- Padding: `px-4 py-3`
- Placeholder: `placeholder:text-[#b8a888]`
- Focus: `focus:border-[#b8892e] focus:ring-2 focus:ring-[#b8892e]/20`

Use native semantic fields with `autocomplete`, `required`, and correct input types.

## Cards And Panels

Use cards sparingly. The site works best with full-width bands and image-led layouts.

Approved card patterns:

- Testimonial cards: `rounded-2xl border border-[#e4dcc8] bg-[#fefdf9] p-4`
- About Us form panel: `rounded-[28px] border border-[#b8892e]/10 bg-[#fdfaf4] shadow-[...]`
- Location info panel: `rounded-2xl border border-[#e4dcc8] bg-[#fefdf9] p-8 sm:p-10`
- Suite cards: image cards with dark gradient overlay and text anchored at bottom.

Avoid placing a card inside another card unless the inner element is a form field or image overlay.

## Imagery

Photography is the primary visual asset.

Rules:

- Use local assets from `public/assets/swarna`.
- Always provide descriptive `alt` text unless decorative.
- Use `loading="lazy"` outside first-viewport hero images.
- Use `object-cover` and fixed responsive heights for gallery rhythm.
- Use dark gradients over photos when text sits on top.
- Avoid decorative SVG/abstract backgrounds when a villa image can carry the section.

Common image styles:

- Hero: `absolute inset-0 size-full object-cover brightness-65`
- Gallery/image card: `rounded-2xl object-cover`
- Overlay suites: `relative overflow-hidden rounded-2xl`, image plus `bg-gradient-to-t from-black/80 via-black/40 to-transparent`

## Motion

Motion is subtle and scroll-led.

Reveal:

- Add `data-reveal` to elements that should fade/slide in.
- Optional delay: `data-reveal-delay="120"`.
- CSS starts with opacity 0 and `translateY(28px)`.
- JS adds `.is-visible` through `IntersectionObserver`.

Parallax:

- Parent can use `data-parallax`.
- Moving image uses `data-parallax-item`.
- Optional speed: `data-parallax-speed="72"` or negative values.
- CSS scales parallax image to `scale(1.12)` to prevent exposed edges.

Respect `prefers-reduced-motion`; current CSS and JS already do this.

## Components

### `x-layout`

Owns document structure, fonts, Vite assets, and body defaults. Every page should use it.

### `x-brand`

Logo link. Supports:

- `variant="light"` for dark/hero/footer backgrounds.
- default dark/gold treatment for light backgrounds.
- custom `href`.

### `x-section-heading`

Use for most section titles.

Props:

- `title`
- `subtitle`
- `level="h2"` or `level="h3"`
- `align="center"` or `align="left"`

### `x-gold-button`

Use for primary booking/check availability CTAs. Pass `href` for link behavior.

### `x-image-card`

Use for standalone gallery images. Pass `src`, `alt`, optional sizing `class`, and optional reveal `delay`.

### `x-feature-item` And `x-location-item`

Use the existing icon system under `resources/views/components/icons`.

### `x-testimonial-card`

Use for guest reviews. Keeps testimonial cards visually consistent and masonry-friendly.

## Accessibility

- Keep headings semantic and ordered.
- Buttons need clear labels; icon-only controls need `aria-label`.
- Images need meaningful `alt`.
- Form fields need visible labels and appropriate autocomplete.
- Focus states must remain visible.
- Text over images must use gradients/overlays for contrast.
- Do not rely on motion to reveal essential information.

## Implementation Guidelines

- Prefer existing Blade components before creating a new component.
- Keep new components in `resources/views/components`.
- Keep page-specific asset helper:

```php
$asset = fn (string $name) => asset("assets/swarna/{$name}");
```

- Use Tailwind classes in Blade, matching current project style.
- Keep arbitrary values when they preserve the Figma/property design precisely.
- Run `php artisan test` after Blade/route changes.
- Run `npm run build` with Node `20.19+` or `22.12+` because Vite 8 requires it.

## Page Patterns

Homepage:

- Transparent hero header that becomes white/glass on scroll.
- Full-viewport photographic hero.
- Editorial heritage section.
- Alternating white and cream bands.
- Gallery with large asymmetric image grid.
- Footer with contact details and navigation.

About Us:

- White/glass fixed header from load.
- Centered editorial intro.
- Rounded concierge form panel with photo sidecar.
- Footer reused from homepage.

When adding a new page, start with the About Us header if the first viewport is light, or the homepage scroll header if the first viewport is a photographic hero.
