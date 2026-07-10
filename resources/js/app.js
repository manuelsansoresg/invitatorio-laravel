/**
 * Invitatorio — landing interactions
 *
 * - Toggle del menú móvil (vanilla JS, sin librerías)
 * - Cierre automático del menú al hacer resize a desktop
 * - Bloqueo de scroll cuando el menú móvil está abierto
 * - IntersectionObserver: aplica animaciones fade-up a medida que entran
 *   en viewport. Evita disparar animación si el usuario pidió reduced motion.
 * - Acordeón FAQ accesible (button + region, atributos ARIA correctos)
 * - Header con sombra al hacer scroll (look & feel premium)
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

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMenu();
    });

    const desktopMq = window.matchMedia('(min-width: 1024px)');
    desktopMq.addEventListener('change', (e) => { if (e.matches) closeMenu(); });

    /* ──────────────────────────────────────────────────────────────────
     * Animaciones on-scroll — data-anim (sistema legacy del hero)
     * ────────────────────────────────────────────────────────────────── */
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const animatedEls = document.querySelectorAll('[data-anim]');

    if (reducedMotion || !('IntersectionObserver' in window)) {
        animatedEls.forEach((el) => {
            el.style.opacity = '1';
            el.style.transform = 'none';
        });
    } else {
        const animObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const delay = el.dataset.animDelay || '0';
                    el.style.animationDelay = `${delay}ms`;
                    el.classList.add(`anim-${el.dataset.anim}`);
                    animObserver.unobserve(el);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        animatedEls.forEach((el) => animObserver.observe(el));
    }

    /* ──────────────────────────────────────────────────────────────────
     * Reveal genérico — cualquier elemento con clase .reveal
     * se anima con opacity/transform al entrar en viewport.
     * ────────────────────────────────────────────────────────────────── */
    const reveals = document.querySelectorAll('.reveal');
    if (reducedMotion || !('IntersectionObserver' in window)) {
        reveals.forEach((el) => el.classList.add('is-visible'));
    } else {
        const revealObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const delay = el.dataset.revealDelay || '0';
                    if (delay !== '0') {
                        el.style.transitionDelay = `${delay}ms`;
                    }
                    el.classList.add('is-visible');
                    obs.unobserve(el);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

        reveals.forEach((el) => revealObserver.observe(el));
    }

    /* ──────────────────────────────────────────────────────────────────
     * FAQ acordeón — accesibilidad completa
     * ────────────────────────────────────────────────────────────────── */
    document.querySelectorAll('.faq-item').forEach((item) => {
        const trigger = item.querySelector('.faq-trigger');
        const panelEl = item.querySelector('.faq-content');
        if (!trigger || !panelEl) return;

        const panelId = trigger.getAttribute('aria-controls');

        trigger.addEventListener('click', () => {
            const open = item.getAttribute('data-open') === 'true';
            item.setAttribute('data-open', open ? 'false' : 'true');
            trigger.setAttribute('aria-expanded', open ? 'false' : 'true');
        });

        // Mantener el ID accesible (ya viene desde Blade, sólo registramos)
        if (panelId) panelEl.setAttribute('id', panelId);
    });

    /* ──────────────────────────────────────────────────────────────────
     * Pestañas de paquetes — Web / Imagen / Video
     * ────────────────────────────────────────────────────────────────── */
    document.querySelectorAll('[data-pricing-tabs]').forEach((tabsRoot) => {
        const tabs = Array.from(tabsRoot.querySelectorAll('[data-pricing-tab]'));
        const panels = Array.from(tabsRoot.querySelectorAll('[data-pricing-panel]'));
        if (!tabs.length || !panels.length) return;

        const activateTab = (selectedTab, shouldFocus = false) => {
            const selectedKey = selectedTab.dataset.pricingTab;

            tabs.forEach((tab) => {
                const active = tab === selectedTab;
                tab.setAttribute('aria-selected', active ? 'true' : 'false');
                tab.classList.toggle('bg-[#EB7512]', active);
                tab.classList.toggle('text-white', active);
                tab.classList.toggle('shadow-md', active);
                tab.classList.toggle('shadow-orange-500/25', active);
                tab.classList.toggle('text-[#5F5A66]', !active);
                tab.classList.toggle('hover:bg-[#FFF1E1]', !active);
                tab.classList.toggle('hover:text-[#EB7512]', !active);
            });

            panels.forEach((panel) => {
                const active = panel.dataset.pricingPanel === selectedKey;
                panel.classList.toggle('hidden', !active);
                panel.toggleAttribute('hidden', !active);

                if (active) {
                    panel.querySelectorAll('.reveal').forEach((el) => {
                        el.classList.add('is-visible');
                    });
                }
            });

            if (shouldFocus) selectedTab.focus({ preventScroll: true });
        };

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => activateTab(tab));
            tab.addEventListener('keydown', (event) => {
                const lastIndex = tabs.length - 1;
                let nextIndex = null;

                if (event.key === 'ArrowRight') nextIndex = index === lastIndex ? 0 : index + 1;
                if (event.key === 'ArrowLeft') nextIndex = index === 0 ? lastIndex : index - 1;
                if (event.key === 'Home') nextIndex = 0;
                if (event.key === 'End') nextIndex = lastIndex;

                if (nextIndex !== null) {
                    event.preventDefault();
                    activateTab(tabs[nextIndex], true);
                }
            });
        });

        const initiallySelected = tabs.find((tab) => tab.getAttribute('aria-selected') === 'true') || tabs[0];
        activateTab(initiallySelected);
    });

    /* ──────────────────────────────────────────────────────────────────
     * Header: ligera sombra al hacer scroll
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

    /* ──────────────────────────────────────────────────────────────────
     * Smooth scroll para links internos (Safari en iOS a veces no respeta)
     * sólo si el navegador es suficientemente viejo, no necesario hoy.
     * ────────────────────────────────────────────────────────────────── */
});
