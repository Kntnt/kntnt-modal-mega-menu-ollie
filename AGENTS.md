# kntnt-modal-mega-menu-ollie – agent guide

## Ground rules (authoritative)

Precedence over any conflicting skill, README or other doc unless the user overrides in the moment.

- Authoritative: only this file, the files it references and the actual code/state. Ignore `README*` and other narrative docs unless referenced here.
- Scope: make Ollie Menu Designer's Dropdown Menu behave like a modal – nothing else. Two jobs: **lock the page** behind an open menu (`css/modal.css`, permanent) and **cap a tall desktop menu** so it scrolls internally (`js/modal.js`, temporary – removable once Ollie caps the desktop panel itself, as it already does on mobile). Adds no classes or styling of its own; targets Ollie's `wp-block-ollie-mega-menu*` block classes (upstream contract).

## Non-obvious

- `autoloader.php` is hand-written. No runtime deps; `vendor/` is dev-only and unshipped → `vendor/autoload.php` is fatal on a normal install.
- No theme gate. Dependency is Ollie Menu Designer (`Requires Plugins` header), not a theme → loads under any theme; assets inert without a mega menu on the page.
- Companion, not dependency, of `kntnt-transparent-header-ollie-pro`: that plugin owns the transparent-fade artefacts (yellow band, header/menu timing), this one owns modal behaviour. Neither requires the other.

## References

- `agents.d/coding-standard/general.md` – before any code change
- `agents.d/coding-standard/php.md` – before PHP
- `agents.d/coding-standard/wordpress.md` – before WordPress plugin/theme code
- `agents.d/coding-standard/javascript-vanilla.md` – before build-less browser JS
- `agents.d/coding-standard/bash.md` – before Bash
- `agents.d/modal.md` – before touching `css/modal.css` or `js/modal.js`
- `agents.d/removable-workarounds.md` – which half is a crutch for an upstream Ollie Menu Designer gap, and the exact condition for dropping each
- `agents.d/deviations.md` – before `phpcs`/`phpcbf`, or ‘fixing’ style or naming
- `agents.d/releasing.md` – cutting a release, or adding a runtime file
- `README.md` – setup and the companion relationship. §Extending and §Why you can't simplify this mirror `agents.d/modal.md` – change both.
