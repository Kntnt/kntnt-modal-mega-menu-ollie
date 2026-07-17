# Kntnt Modal Mega Menu for Ollie

[![Requires WordPress: 6.5+](https://img.shields.io/badge/WordPress-6.5+-blue.svg)](https://wordpress.org)
[![Requires PHP: 8.3+](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![License: GPL v2+](https://img.shields.io/badge/License-GPLv2+-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
[![Latest release](https://img.shields.io/github/v/release/Kntnt/kntnt-modal-mega-menu-ollie)](https://github.com/Kntnt/kntnt-modal-mega-menu-ollie/releases/latest)

Makes the Dropdown Menu from Ollie Menu Designer behave like a modal: it locks the page behind an open menu and lets a tall menu scroll inside itself.

## Description

Ollie Menu Designer's Dropdown Menu (its mega menu) opens as an ordinary dropdown. Two things follow from that, and both feel like bugs to a visitor.

The page behind the open menu keeps scrolling. So when a visitor moves the wheel or swipes expecting to scroll the menu, the page scrolls instead and the menu appears to drift. And on desktop a menu taller than the browser window simply runs off the bottom of the screen: the lower part is there, but there is no way to reach it, because the panel is anchored inside the (sticky) header and the page won't bring it into view.

This plugin makes the open menu behave the way a modal does. While a menu is open the page is locked, so scrolling stays inside the menu. And a menu taller than the viewport is capped to the space below it and scrolls internally, so nothing is unreachable.

It does this for **every** Dropdown Menu on the site, with no configuration. There is nothing to switch on per menu or per template.

## Requirements

- WordPress 6.5 or later.
- PHP 8.3 or later.
- [Ollie Menu Designer](https://olliewp.com/), which provides the Dropdown Menu block. The plugin declares it as a required plugin, so WordPress will not let you activate this without it.

Ollie Menu Designer is part of the Ollie ecosystem, but the block itself is not tied to the Ollie theme, so this plugin does not check the theme.

## Installation

1. Download [kntnt-modal-mega-menu-ollie.zip](https://github.com/Kntnt/kntnt-modal-mega-menu-ollie/releases/latest/download/kntnt-modal-mega-menu-ollie.zip).
2. In **Plugins → Add New Plugin → Upload Plugin**, upload the zip and activate it.

That is all. There is no settings page and no Site Editor step: the plugin works on every Dropdown Menu as soon as it is active. It also updates itself from this repository's releases, appearing under **Dashboard → Updates** like any other plugin.

## Using it with a transparent header

This plugin is a companion to [Kntnt Transparent Header for Ollie Pro](https://github.com/Kntnt/kntnt-transparent-header-ollie-pro), not a dependency of it, and the two are designed to compose.

That plugin makes the transition between a transparent and a solid header look right when a mega menu opens over it — the yellow band where two fading layers overlap, and the header and menu arriving in step. This plugin handles the general modal behaviour that has nothing to do with transparency: the scroll lock and the internal scroll.

They cooperate without knowing about each other. The transparent-header plugin may hang the panel off the header's bottom edge; this plugin measures the panel's actual position before capping its height, so whatever the other plugin sets, the cap is taken from there down. Install either, both, or neither.

## Extending

The plugin is deliberately knob-free, but two things are worth knowing.

**The scrollbar jump.** Locking the page removes the scrollbar while a menu is open. On systems with overlay scrollbars (macOS by default) nothing shifts. On systems with classic scrollbars (typically Windows) the page can jump sideways by the scrollbar's width as it is removed and again as it returns. If that bothers you, reserve the gutter in your own CSS so the width is always accounted for:

```css
html {
  scrollbar-gutter: stable;
}
```

This is left to you rather than shipped, because reserving the gutter is a visible layout choice that belongs with your design, not with the plugin.

**Opting a menu out.** There is no per-menu setting. If you need one specific menu to keep the page scrollable, or not to be capped, override it in your own CSS or a fork — the behaviour hangs on Ollie Menu Designer's own block classes, which are stable selectors.

## Known limitations

- **The internal scroll is desktop only.** On mobile, Ollie Menu Designer already turns the open menu into a full-screen, scrollable overlay; the plugin recognises that panel and leaves it alone. The scroll lock applies everywhere.
- **The menu is measured 100ms after it opens**, to let Ollie finish animating it in before its height is read. On a very slow device the cap could in theory land a frame late; in practice it is imperceptible.
- **No per-menu opt-out.** The behaviour is all or nothing across the site (see *Extending*).

## Why you can't simplify this

A few decisions look like they could be trimmed. They can't.

| Looks like you could… | Why it stays |
| --- | --- |
| Cap the menu at Ollie's default position instead of measuring it. | The panel's top is measured live because another plugin (a transparent header) may move it. Assume the default and the cap is wrong the moment the panel is repositioned — the menu spills past the bottom again by exactly the offset. |
| Lock only the `body`. | Either the root element or the `body` can be the scroll container depending on the theme. Locking only one silently fails to lock on the other kind of site. Locking both is safe: the one that is not the scroller is a no-op. |
| Cap the mobile menu too. | Ollie's mobile overlay is `position: fixed` with its own `overflow-y: scroll`. Capping it fights Ollie. The clamp skips any `fixed` panel for exactly that reason. |
| Add a stylesheet dependency or a special enqueue order. | The scroll-lock rule outranks Ollie Menu Designer's own `body:has()` rule on specificity, so source order doesn't decide it. A dependency would imply an ordering that isn't there. |
| Drop the internal-scroll script — surely Ollie handles tall menus. | Only on mobile. On desktop Ollie gives the panel `overflow: visible` and no `max-height`, so a tall menu is unreachable. This script is a temporary stand-in until Ollie Menu Designer caps the desktop panel itself; see below. |

### The internal scroll is a temporary workaround

The scroll lock is the plugin's permanent job. The internal scroll is not: it only fills a gap in Ollie Menu Designer, which caps its panel on mobile but not on desktop. That has been reported to OllieWP. When a release of Ollie Menu Designer handles the desktop panel the way it already handles mobile, `js/modal.js` can be removed and the plugin keeps doing the one thing that will always be its own: locking the page behind an open menu.

## Development

See [CONTRIBUTING.md](CONTRIBUTING.md) for the coding standards, the quality gate, and how releases are built.

## File map

```
kntnt-modal-mega-menu-ollie.php   Plugin header, PHP guard, bootstrap
autoloader.php                    Hand-written PSR-4 autoloader
classes/Plugin.php                Singleton; enqueues the two assets
classes/Updater.php               Self-update from GitHub releases
css/modal.css                     The scroll lock. Adds no styling
js/modal.js                       Caps a tall desktop menu; measures live
```

## Frequently asked questions

**Does it work without the Ollie theme?**
Yes. The Dropdown Menu comes from Ollie Menu Designer, which is what the plugin extends; the theme is not checked.

**Do I need the transparent-header plugin?**
No. The two are independent companions. Use either, both, or neither.

**Where are the settings?**
There are none. Activate it and it works on every Dropdown Menu.

## Support

Have a usage question or something to discuss? Please use [Discussions](https://github.com/Kntnt/kntnt-modal-mega-menu-ollie/discussions).

Found a bug or want to request a feature? Please [open an issue](https://github.com/Kntnt/kntnt-modal-mega-menu-ollie/issues). Search the existing issues first to avoid duplicates.

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
