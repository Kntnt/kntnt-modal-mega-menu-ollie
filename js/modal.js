/**
 * Caps an open mega menu to the space below it, so a tall menu scrolls inside
 * itself instead of running off the bottom of the screen.
 *
 * On desktop Ollie Menu Designer gives the open panel `overflow: visible` and
 * no `max-height`, so a menu taller than the viewport spills past the fold with
 * no way to reach the rest. The mobile overlay already scrolls (it is
 * `position: fixed` with `overflow-y: scroll`), so this fills only the desktop
 * gap and leaves the mobile panel to Ollie.
 *
 * This is a temporary workaround for an upstream gap: it can be removed once
 * Ollie Menu Designer caps the desktop panel itself, the way it already does on
 * mobile. The scroll lock that makes the menu modal lives in css/modal.css and
 * is the plugin's permanent job.
 *
 * The panel's top is measured live, never assumed, so this composes with any
 * other code that repositions the panel (for example a transparent-header
 * plugin hanging it off the header's bottom edge): whatever the top ends up
 * being, the cap is taken from there down.
 */
(() => {

    'use strict';

    const menus = [...document.querySelectorAll('.wp-block-ollie-mega-menu')];

    if (menus.length === 0) {
        return;
    }

    const SETTLE_DELAY = 100; // Let the menu finish opening before measuring it.

    /**
     * Caps every open, desktop-mode mega menu to the viewport space below it.
     *
     * @returns {void}
     */
    const clampOpenMenus = () => {
        menus.forEach(menu => {
            const panel = menu.querySelector('.wp-block-ollie-mega-menu__menu-container');

            // Skip a panel that isn't laid out, and skip the mobile overlay:
            // Ollie already gives its fixed full-screen panel its own scroll, so
            // capping it would fight that.
            if (!panel || panel.offsetParent === null || getComputedStyle(panel).position === 'fixed') {
                return;
            }

            // Measure the panel's actual top – whatever set it – and cap it to
            // the space left below, letting the overflow scroll internally.
            const remainingHeight = window.innerHeight - panel.getBoundingClientRect().top;

            panel.style.setProperty('max-height', `${remainingHeight}px`, 'important');
            panel.style.setProperty('overflow-y', 'auto', 'important');
        });
    };

    // A menu's available height is only knowable once it has opened.
    menus.forEach(menu => {
        const toggle = menu.querySelector('.wp-block-ollie-mega-menu__toggle');

        if (!toggle || !menu.querySelector('.wp-block-ollie-mega-menu__menu-container')) {
            return;
        }

        new MutationObserver(() => {
            if (toggle.getAttribute('aria-expanded') === 'true') {
                setTimeout(clampOpenMenus, SETTLE_DELAY);
            }
        }).observe(toggle, { attributes: true, attributeFilter: ['aria-expanded'] });
    });

    // A resize changes the space below an open menu, so re-cap on the way.
    window.addEventListener('resize', clampOpenMenus, { passive: true });

})();
