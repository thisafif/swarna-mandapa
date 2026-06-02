import re

filepath = r'c:\laragon\www\swarna-mandapa-promo\swarna-mandapa\resources\views\booking\form.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

replacements = {
    # Variables in style block
    r'var\(--cream\)': 'var(--color-cream)',
    r'var\(--cream-dark\)': 'var(--color-cream-dark)',
    r'var\(--gold\)': 'var(--color-gold)',
    r'var\(--gold-light\)': 'var(--color-gold-light)',
    r'var\(--gold-pale\)': 'var(--color-gold-pale)',
    r'var\(--border\)': 'var(--color-border)',
    r'var\(--text-dark\)': 'var(--color-text-dark)',
    r'var\(--text-mid\)': 'var(--color-text-mid)',
    r'var\(--text-muted\)': 'var(--color-text-muted)',
    r'var\(--success\)': 'var(--color-success)',
    r'var\(--danger\)': 'var(--color-danger)',
    r'var\(--shadow-md\)': '0 4px 20px rgba(44,36,22,.10)',
    r'var\(--radius-sm\)': '6px',
    r'var\(--radius-md\)': '12px',
    r'var\(--radius-lg\)': '20px',

    # Grid & Layout
    r'class="container pb-5"': 'class="container mx-auto px-4 pb-12"',
    r'row g-4 align-items-start': 'grid grid-cols-1 lg:grid-cols-12 gap-6 items-start',
    r'col-lg-7': 'lg:col-span-7',
    r'col-lg-5': 'lg:col-span-5',
    
    r'row g-3 mb-3': 'grid grid-cols-12 gap-4 mb-4',
    r'row g-3': 'grid grid-cols-12 gap-4',
    r'class="col-6"': 'class="col-span-6"',
    r'class="col-12"': 'class="col-span-12"',
    r'class="col-md-4"': 'class="col-span-12 md:col-span-4"',
    r'class="col-md-6"': 'class="col-span-12 md:col-span-6"',

    # Flexbox
    r'd-flex': 'flex',
    r'flex-wrap': 'flex-wrap',
    r'align-items-center': 'items-center',
    r'align-items-start': 'items-start',
    r'justify-content-center': 'justify-center',
    r'justify-content-between': 'justify-between',
    r'flex-grow-1': 'grow',

    # Spacing
    r'mb-3': 'mb-4',
    r'mb-4': 'mb-6',
    r'mb-2': 'mb-2',
    r'mt-2': 'mt-2',
    r'mt-3': 'mt-4',
    r'p-3': 'p-4',
    r'p-0': 'p-0',
    r'px-2': 'px-2',
    r'px-3': 'px-4',
    r'py-1': 'py-1',
    r'g-3': 'gap-4',
    r'g-4': 'gap-6',
    r'gap-1': 'gap-1',
    r'gap-2': 'gap-2',
    r'me-1': 'mr-1',
    r'me-2': 'mr-2',
    r'ms-1': 'ml-1',
    r'ms-2': 'ml-2',
    r'start-0': 'left-0',
    r'end-0': 'right-0',
    
    # Typography
    r'fw-500': 'font-medium',
    r'fw-600': 'font-semibold',
    r'fw-700': 'font-bold',
    r'text-center': 'text-center',
    r'fs-4': 'text-2xl',
    
    # Others
    r'w-100': 'w-full',
    r'img-fluid': 'max-w-full h-auto',
    r'rounded-2': 'rounded',
    r'rounded-3': 'rounded-xl',
    r'position-relative': 'relative',
    r'position-absolute': 'absolute',
    r'bottom-0': 'bottom-0',
    r'd-none': 'hidden',
    r'd-sm-inline': 'sm:inline',
    r'text-white': 'text-white',
    r'opacity-50': 'opacity-50',
    r'opacity-75': 'opacity-75',
    r'input-group-text': 'px-3 py-2 flex items-center bg-[var(--color-cream)] border border-[var(--color-border)] rounded-l-md text-[.85rem] text-[var(--color-text-dark)]',
    r'input-group': 'flex w-full',
}

for pattern, replacement in replacements.items():
    content = re.sub(pattern, replacement, content)

# Special fixes
content = content.replace('style="border-left:0;"', 'style="border-left:0; border-top-left-radius:0; border-bottom-left-radius:0;"')

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print("Conversion complete.")
