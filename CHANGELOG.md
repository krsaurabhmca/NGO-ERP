# Changelog

All notable changes to this project will be documented in this file.

## [1.0.2] - 2026-09-17

### Changed
- Upgraded the Auth Login Screen to use the new animated vibrant aesthetic and fixed a bug where the text was invisible.

### Fixed
- Fixed an issue where the System Updater would fail to find the latest GitHub release if the configured repository URL ended with `.git`.

## [1.0.1] - 2026-09-17

### Added
- New **Animated & Vibrant** hero section with dynamic breathing gradients and floating glowing shapes.
- New smooth vector wave dividers using mathematically perfect inline SVGs for the Hero slider and Footer sections.
- Comprehensive dynamic color theming across the entire application using CSS variables.
- Six new curated premium color presets (Midnight & Teal, Slate & Neon Blue, Forest & Mint, Obsidian & Amber, Royal Purple & Gold, Deep Crimson & Rose).
- Dual-color overlapping swatches in the admin theme settings UI.

### Changed
- Refactored all views (`app/views/**/*.php`) to remove hardcoded hex colors and use CSS variables `var(--primary)` and `var(--accent)`.
- Updated `admin/settings/update.php` to include the standard admin sidebar and topbar.
- Replaced all jagged CSS mask vectors and static gradients with smooth, infinitely scalable vector designs.

### Fixed
- Fixed an issue where the footer gradient and hero slider gradient were causing rendering artifacts on certain screen sizes.
