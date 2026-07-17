# Removable workarounds — what to drop when upstream catches up

Read when Ollie Menu Designer moves. Everything this plugin does is a stand-in for something Ollie Menu Designer could own itself; each entry names the exact upstream change that makes it redundant. The mechanics and the measurement reasoning live in `modal.md` – this file is only the removal roadmap.

The two halves retire on different horizons: the desktop cap is a **bug workaround** that comes out on its own while the plugin lives on; the scroll lock is the **feature** the plugin exists for – if Ollie ships that natively, the whole plugin retires, not one file.

## The bugfix — drop `js/modal.js` when Ollie caps the desktop panel

`js/modal.js` caps a tall desktop menu to the space below it so it scrolls inside itself. It exists only because Ollie Menu Designer caps the panel on **mobile** (the `position: fixed` overlay has its own `overflow-y: scroll`) but not on **desktop**, where it leaves the panel `overflow: visible` with no `max-height`, so a menu taller than the viewport spills past the fold unreachably. Reported to OllieWP.

| In the code | Works around | Delete it when |
|---|---|---|
| `js/modal.js` – the whole clamp, plus its `wp_enqueue_script` in `classes/Plugin.php` (`enqueue_assets`) | **Ollie Menu Designer** caps only the mobile overlay, never the desktop panel. | Ollie caps the desktop panel's height the way it already does on mobile. Check: with this script removed, open a menu taller than the viewport on desktop – the panel stops at the viewport edge and scrolls internally on its own. Then delete the file and its enqueue; `js/` is now empty, so drop the `js` entry from `KEEP` in `build-release-zip.sh` too. |

The scroll lock in `css/modal.css` is unaffected – it stays as long as the plugin does (below).

## The feature — retire the plugin when the Dropdown Menu goes modal natively

`css/modal.css` locks the page behind an open menu so the Dropdown Menu behaves like a modal instead of an ordinary dropdown that scrolls the page underneath. This is the plugin's reason to exist, not a bug: Ollie Menu Designer's Dropdown Menu simply has no modal mode. A hoped-for upstream feature, not a filed bug.

There is nothing to delete surgically here short of the whole plugin. If Ollie Menu Designer's Dropdown Menu gains a native modal / page-lock option, this plugin is redundant. Check: enable that option upstream, open a menu, try to scroll – with `css/modal.css` removed, the page behind stays put on its own. Then retire the plugin (and `js/modal.js` with it, if it was still present).
