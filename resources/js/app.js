/**
 * Invitatorio — landing interactions
 *
 * - Toggle del menú móvil (vanilla JS, sin librerías)
 * - Cierre automático del menú al hacer resize a desktop
 * - Bloqueo de scroll cuando el menú móvil está abierto
 * - IntersectionObserver: aplica animaciones fade-up a medida que entran
 *   en viewport. Evita disparar animación si el usuario pidió reduced motion.
 */

document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    /* ──────────────────────────────────────────────────────────────────
     * Menú móvil
     * ────────────────────────────────────────────────────────────────── */
    const toggleBtn = document.querySelector('[data-menu-toggle]');
    const closeBtn  = document.querySelector('[data-menu-close]');
    const panel     = document.querySelector('[data-menu-panel]');
    const backdrop  = document.querySelector('[data-menu-backdrop]');
    const navLinks  = document.querySelectorAll('[data-menu-link]');

    const openMenu = () => {
        if (!panel || !backdrop || !toggleBtn) return;
        panel.classList.remove('translate-x-full');
        panel.classList.add('translate-x-0');
        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100');
        document.body.classList.add('overflow-hidden');
        toggleBtn.setAttribute('aria-expanded', 'true');
        // Enfoca el primer link para teclado/accesibilidad
        const firstLink = panel.querySelector('a, button');
        firstLink?.focus({ preventScroll: true });
    };

    const closeMenu = () => {
        if (!panel || !backdrop || !toggleBtn) return;
        panel.classList.add('translate-x-full');
        panel.classList.remove('translate-x-0');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        backdrop.classList.remove('opacity-100');
        document.body.classList.remove('overflow-hidden');
        toggleBtn.setAttribute('aria-expanded', 'false');
        toggleBtn.focus({ preventScroll: true });
    };

    toggleBtn?.addEventListener('click', () => {
        const expanded = toggleBtn.getAttribute('aria-expanded') === 'true';
        expanded ? closeMenu() : openMenu();
    });

    closeBtn?.addEventListener('click', closeMenu);
    backdrop?.addEventListener('click', closeMenu);
    navLinks.forEach((link) => link.addEventListener('click', closeMenu));

    // Cerrar con Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMenu();
    });

    // Autocierre al volver a desktop
    const desktopMq = window.matchMedia('(min-width: 1024px)');
    const handleMqChange = (e) => { if (e.matches) closeMenu(); };
    desktopMq.addEventListener('change', handleMqChange);

    /* ──────────────────────────────────────────────────────────────────
     * Animaciones on-scroll para [data-anim]
     * ────────────────────────────────────────────────────────────────── */
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const animatedEls = document.querySelectorAll('[data-anim]');

    if (reducedMotion || !('IntersectionObserver' in window)) {
        // Sin observer o sin motion permitido: simplemente los hacemos visibles.
        animatedEls.forEach((el) => {
            el.style.opacity = '1';
            el.style.transform = 'none';
        });
    } else {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const delay = el.dataset.animDelay || '0';
                    el.style.animationDelay = `${delay}ms`;
                    el.classList.add(`anim-${el.dataset.anim}`);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        animatedEls.forEach((el) => observer.observe(el));
    }

    /* ──────────────────────────────────────────────────────────────────
     * Header: ligera sombra al hacer scroll (look & feel premium)
     * ────────────────────────────────────────────────────────────────── */
    const header = document.querySelector('[data-header]');
    if (header) {
        const onScroll = () => {
            if (window.scrollY > 12) header.classList.add('is-scrolled');
            else header.classList.remove('is-scrolled');
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }
});
