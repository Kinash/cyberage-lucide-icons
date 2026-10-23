# CyberAge Lucide Icons: kickoff (right-sized system-blueprint)

**Frame.** A small, reusable WordPress plugin that adds the Lucide icon set to Elementor's icon picker and a `[lucide]` shortcode, rendering inline SVG. First used on the LEA-WH Fellowship website; built for any CyberAge client site.

**Riskiest assumption:** that Elementor lets a custom icon tab output inline SVG instead of an icon font. **Proved** by spike S1 (16 Oct 2026, Elementor 4.3.4, WordPress 7.1.3, lucide-static 1.58.0) through the tab's `render_callback`.

## Requirements (locked)

| # | Requirement | Decided |
|---|---|---|
| R1 | Lucide tab in Elementor's icon picker; inline SVG output; colour via currentColor; decorative icons aria-hidden | Benson, 30 Sep 2026 |
| R2 | Icons bundled (pinned Lucide version, no CDN); build script regenerates set + release notes | 30 Sep 2026 |
| R3 | Old names keep resolving (alias map), because Elementor stores icons by name | 30 Sep 2026 |
| R4 | `[lucide]` shortcode | 30 Sep 2026 |
| R5 | Updates via Plugin Update Checker + releases on a **public** GitHub repo (no tokens on client sites) | 30 Sep 2026; repo Kinash/cyberage-lucide-icons approved 23 Oct 2026 |
| R6 | Social icons stay on Elementor's Font Awesome Brands, not Lucide | 30 Sep 2026 |
| R7 | Name "CyberAge Lucide Icons", slug `cyberage-lucide-icons` | Benson, 23 Oct 2026 |

## Design

- `includes/class-icon-library.php`: resolves names (incl. aliases) and builds the SVG. One source of markup.
- `includes/elementor.php`: tab registration, render callback, preview CSS in the editor only.
- `includes/shortcode.php`: `[lucide]`.
- `includes/updates.php`: Plugin Update Checker v5.7 → GitHub release asset `cyberage-lucide-icons.zip`.
- `tools/build-icons.mjs` → `assets/` (icons.json, aliases.json, picker.json, editor.css, manifest.json) + `docs/RELEASE-NOTES-lucide-<v>.md`.
- `tools/release.mjs` → `dist/cyberage-lucide-icons.zip` (plugin files only).

## Risks

| Risk | Mitigation |
|---|---|
| Lucide renames/drops an icon used on a live page | Alias map built automatically; build fails (exit 1) on a vanished name until mapped in `tools/aliases-manual.json` |
| Elementor changes the icon tab API | Pin tested Elementor versions per site lock; test every release on a Laragon copy |
| Editor CSS size (~0.9 MB) | Editor and preview only; visitors load none |
| Elementor caches old icon markup | Clear Files & Data after output changes (documented) |

## Gate B (must pass before v1.0.0 is used on LEA-WH)

1. Zip installs and activates on Laragon with no PHP notices.
2. The picker shows the Lucide tab and its icons.
3. The front end outputs inline `<svg>` (Icon, Icon Box, Button); an old name renders its new icon.
4. Publishing v1.0.1 shows an update notice on the Laragon site.
5. `php-comment-audit` clean; no tokens or keys in the repo.
