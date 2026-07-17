Read before running `phpcs`/`phpcbf`, or ‘fixing’ style or naming.

`phpcs.xml.dist` documents each exclusion at the exclusion. Only what it cannot say lives here.

- `composer phpcs` must be **silent**: 0 errors, 0 warnings. Warnings fail the gate. Never add `ignore_warnings_on_exit`.
- **`agents.d/coding-standard/php.md` is locally amended** (*Line width*). `/coding-standard --update` reverts it – re-apply until [kntnt-code-skills#37](https://github.com/Kntnt/kntnt-code-skills/issues/37) ships. Only hand-edited file in `agents.d/coding-standard/`; the rest are plugin-owned – never touch.
- **Plugin header block is exempt from the 80-col comment rule.** WordPress parses one field per line, so `Plugin URI` (81) and `Description` (198) cannot wrap. Everything else conforms.
- Translators comment sits **directly** above its `__()` call – nothing between, not even an annotation.
- The plugin defines **no CSS classes of its own**, so `general.md`'s `kntnt-` prefix rule has nothing to apply to. Its selectors reference Ollie Menu Designer's block classes (`wp-block-ollie-mega-menu*`) verbatim – an upstream contract, not ours to prefix or rename.
