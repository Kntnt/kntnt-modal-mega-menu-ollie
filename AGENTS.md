# kntnt-modal-mega-menu-ollie – agent guide

## Ground rules (authoritative)

Precedence over any conflicting skill, README or other doc unless the user overrides in the moment.

- Authoritative: only this file, the files it references and the actual code/state. Ignore `README*` and other narrative docs unless referenced here.
- The plugin makes Ollie Menu Designer's Dropdown Menu behave like a modal, and nothing else. Two behaviours: it **locks the page** behind an open menu (`css/modal.css` – the permanent job), and it **caps a tall desktop menu** so it scrolls inside itself (`js/modal.js` – a temporary workaround, removable once Ollie Menu Designer caps the desktop panel itself the way it already does on mobile).
- Adds no classes and no styling of its own. It targets Ollie Menu Designer's own block classes (`wp-block-ollie-mega-menu*`) – an upstream contract. If OllieWP renames them the selectors stop matching; that block is the thing to follow, not to route around.

## Non-obvious

- `autoloader.php` stays hand-written. No runtime deps; `vendor/` is dev-only and unshipped → `vendor/autoload.php` is fatal on a normal install.
- No theme gate. The dependency is Ollie Menu Designer (enforced by the `Requires Plugins` header), not a theme, so the plugin loads under any theme; the assets are inert without a mega menu on the page.
- The clamp measures the panel's **live** top, never Ollie's default, so it composes with a transparent-header plugin that repositions the panel. Never hard-code a top offset.
- Companion, not dependency, of `kntnt-transparent-header-ollie-pro`. That plugin owns the transparent-fade artefacts (the yellow band, the header/menu timing); this one owns general modal behaviour. Neither requires the other.

## References

- `agents.d/coding-standard/general.md` – before any code change
- `agents.d/coding-standard/php.md` – before PHP
- `agents.d/coding-standard/wordpress.md` – before WordPress plugin/theme code
- `agents.d/coding-standard/javascript-vanilla.md` – before build-less browser JS
- `agents.d/coding-standard/bash.md` – before Bash
- `agents.d/modal.md` – before touching `css/modal.css` or `js/modal.js`
- `agents.d/deviations.md` – before `phpcs`/`phpcbf`, or ‘fixing’ style or naming
- `agents.d/releasing.md` – cutting a release, or adding a runtime file
- `README.md` – setup and the companion relationship. §Extending and §Why you can't simplify this are mirrored by `agents.d/modal.md` – change both.
