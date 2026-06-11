import './bootstrap';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';
import '@fortawesome/fontawesome-free/css/all.css';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!prefersReducedMotion) {
    new Lenis({
        autoRaf: true,
        anchors: true,
    });
}

const scrollHeader = document.querySelector('[data-scroll-header]');

if (scrollHeader) {
    const menuButton = scrollHeader.querySelector('[data-scroll-menu]');
    const cta = scrollHeader.querySelector('[data-scroll-cta]');
    let headerFrame = null;

    const updateHeader = () => {
        headerFrame = null;
        const isScrolled = window.scrollY > 1;

        scrollHeader.classList.toggle('bg-white/90', isScrolled);
        scrollHeader.classList.toggle('shadow-sm', isScrolled);
        scrollHeader.classList.toggle('backdrop-blur', isScrolled);

        menuButton?.classList.toggle('text-white', !isScrolled);
        menuButton?.classList.toggle('text-[#71562a]', isScrolled);

        cta?.classList.toggle('bg-white', !isScrolled);
        cta?.classList.toggle('text-[#c5a858]', !isScrolled);
        cta?.classList.toggle('bg-[#c5a858]', isScrolled);
        cta?.classList.toggle('text-white', isScrolled);
        cta?.classList.toggle('hover:bg-[#ffdc7d]', !isScrolled);
        cta?.classList.toggle('hover:bg-[#b8892e]', isScrolled);
    };

    const requestHeaderUpdate = () => {
        if (headerFrame !== null) {
            return;
        }

        headerFrame = window.requestAnimationFrame(updateHeader);
    };

    updateHeader();
    window.addEventListener('scroll', requestHeaderUpdate, { passive: true });
}

document.querySelectorAll('[data-nav-panel]').forEach((panel) => {
    const drawer = panel.querySelector('[data-nav-drawer]');
    const backdrop = panel.querySelector('[data-nav-backdrop]');
    const closeButtons = panel.querySelectorAll('[data-nav-close], [data-nav-link]');
    const toggles = document.querySelectorAll('[data-nav-toggle]');
    let activeToggle = null;
    let closeTimer = null;
    let previousBodyOverflow = '';

    const setOpenState = (isOpen) => {
        toggles.forEach((toggle) => toggle.setAttribute('aria-expanded', String(isOpen)));
        panel.setAttribute('aria-hidden', String(!isOpen));
    };

    const openPanel = (toggle) => {
        if (!drawer || !backdrop) {
            return;
        }

        activeToggle = toggle;
        previousBodyOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        window.clearTimeout(closeTimer);
        panel.classList.remove('hidden');
        setOpenState(true);

        window.requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            drawer.classList.remove('-translate-x-full');
            drawer.focus({ preventScroll: true });
        });
    };

    const closePanel = () => {
        if (!drawer || !backdrop || panel.classList.contains('hidden')) {
            return;
        }

        setOpenState(false);
        backdrop.classList.add('opacity-0');
        drawer.classList.add('-translate-x-full');
        document.body.style.overflow = previousBodyOverflow;

        closeTimer = window.setTimeout(() => {
            panel.classList.add('hidden');
            activeToggle?.focus({ preventScroll: true });
            activeToggle = null;
        }, 300);
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => openPanel(toggle));
    });

    backdrop?.addEventListener('click', closePanel);
    closeButtons.forEach((button) => button.addEventListener('click', closePanel));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closePanel();
        }
    });
});

document.querySelectorAll('[data-gallery-tabs]').forEach((gallery) => {
    const tabs = Array.from(gallery.querySelectorAll('[data-gallery-tab]'));
    const panels = Array.from(gallery.querySelectorAll('[data-gallery-panel]'));

    const activateTab = (selectedId) => {
        tabs.forEach((tab) => {
            const isActive = tab.dataset.galleryTab === selectedId;

            tab.setAttribute('aria-selected', String(isActive));
            tab.classList.toggle('border-[#b8892e]', isActive);
            tab.classList.toggle('bg-[#b8892e]', isActive);
            tab.classList.toggle('text-white', isActive);
            tab.classList.toggle('shadow-[0_4px_12px_rgba(184,137,46,0.22)]', isActive);
            tab.classList.toggle('border-[#e4dcc8]', !isActive);
            tab.classList.toggle('bg-[#fefdf9]', !isActive);
            tab.classList.toggle('text-[#71562a]', !isActive);
            tab.classList.toggle('hover:border-[#c5a858]', !isActive);
            tab.classList.toggle('hover:text-[#9e6e42]', !isActive);
        });

        panels.forEach((panel) => {
            const isActive = panel.dataset.galleryPanel === selectedId;

            panel.classList.toggle('grid', isActive);
            panel.classList.toggle('hidden', !isActive);
        });
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => activateTab(tab.dataset.galleryTab));
    });
});

document.querySelectorAll('[data-mailto-form]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
            return;
        }

        event.preventDefault();

        const formData = new FormData(form);
        const firstName = String(formData.get('first_name') || '').trim();
        const lastName = String(formData.get('last_name') || '').trim();
        const email = String(formData.get('email') || '').trim();
        const phone = String(formData.get('phone') || '').trim();
        const message = String(formData.get('message') || '').trim();
        const fullName = [firstName, lastName].filter(Boolean).join(' ');

        const subject = `Swarna Mandapa enquiry${fullName ? ` from ${fullName}` : ''}`;
        const body = [
            'Hello Swarna Mandapa Concierge Team,',
            '',
            'I would like to enquire about staying at Swarna Mandapa.',
            '',
            'Guest details:',
            `Name: ${fullName || '-'}`,
            `Email: ${email || '-'}`,
            `Phone: ${phone || '-'}`,
            '',
            'Message:',
            message || '-',
            '',
            'Thank you,',
            fullName || 'A prospective guest',
        ].join('\n');

        window.location.href = `mailto:reservations@swarnamandapa.com?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    });
});

document.querySelectorAll('[data-review-mailto-form]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
            return;
        }

        event.preventDefault();

        const formData = new FormData(form);
        const firstName = String(formData.get('first_name') || '').trim();
        const lastName = String(formData.get('last_name') || '').trim();
        const email = String(formData.get('email') || '').trim();
        const rating = String(formData.get('rating') || '5').trim();
        const review = String(formData.get('review') || '').trim();
        const fullName = [firstName, lastName].filter(Boolean).join(' ');

        const subject = `Swarna Mandapa guest review${fullName ? ` from ${fullName}` : ''}`;
        const body = [
            'Hello Swarna Mandapa Team,',
            '',
            'I would like to share a review of my stay.',
            '',
            'Guest details:',
            `Name: ${fullName || '-'}`,
            `Email: ${email || '-'}`,
            `Rating: ${rating}/5`,
            '',
            'Review:',
            review || '-',
            '',
            'Thank you,',
            fullName || 'A Swarna Mandapa guest',
        ].join('\n');

        window.location.href = `mailto:reservations@swarnamandapa.com?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    });
});

const revealItems = Array.from(document.querySelectorAll('[data-reveal]'));

if (prefersReducedMotion || !('IntersectionObserver' in window)) {
    revealItems.forEach((item) => item.classList.add('is-visible'));
} else {
    revealItems.forEach((item) => {
        const delay = Number.parseInt(item.dataset.revealDelay || '0', 10);
        item.style.setProperty('--reveal-delay', `${delay}ms`);
    });

    const revealObserver = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        {
            rootMargin: '0px 0px -10% 0px',
            threshold: 0.12,
        },
    );

    revealItems.forEach((item) => revealObserver.observe(item));
}

const parallaxItems = Array.from(document.querySelectorAll('[data-parallax-item]')).map((item) => ({
    element: item,
    speed: Number.parseFloat(item.dataset.parallaxSpeed || '56'),
}));

if (!prefersReducedMotion && parallaxItems.length > 0) {
    let parallaxFrame = null;

    const updateParallax = () => {
        parallaxFrame = null;
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

        parallaxItems.forEach(({ element, speed }) => {
            const rect = element.parentElement.getBoundingClientRect();
            const progress = (viewportHeight - rect.top) / (viewportHeight + rect.height) - 0.5;
            const clampedProgress = Math.min(Math.max(progress, -0.5), 0.5);
            const offset = clampedProgress * speed;

            element.style.setProperty('--parallax-y', `${offset.toFixed(2)}px`);
        });
    };

    const requestParallaxUpdate = () => {
        if (parallaxFrame !== null) {
            return;
        }

        parallaxFrame = window.requestAnimationFrame(updateParallax);
    };

    updateParallax();
    window.addEventListener('scroll', requestParallaxUpdate, { passive: true });
    window.addEventListener('resize', requestParallaxUpdate);
}
