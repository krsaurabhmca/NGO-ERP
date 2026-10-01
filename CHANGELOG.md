# Changelog

All notable changes to this project will be documented in this file.

## [1.1.3] - 2026-10-01

### Added
- Added Cashfree webhook integration for real-time payment status updates (`/webhook/cashfree`).
- Displayed the Cashfree Webhook URL inside the admin Payment Settings page for easy configuration.

### Fixed
- Fixed an issue where the CSRF token was consumed during Cashfree order creation, causing the subsequent verification request to fail with an "Invalid or expired form token" error.
- Ensured Cashfree verification correctly uses `CURLOPT_SSL_VERIFYPEER` settings to support local and varied SSL environments without curl errors.
- Cleaned up redundant implementation in `HomeController` to properly route all Cashfree callbacks.

## [1.0.7] - 2026-10-01

### Added
- Integrated Cashfree payment gateway for online donations.
- Added dynamic Active Gateway selector in admin payment settings.
- Added "Cashfree" filtering capability in Admin Donations list and aggregate Cashfree total tracking in Dashboard.

### Changed
- Improved Payment Settings UI (`admin/settings/payments`) by compartmentalizing gateway configurations. Now securely hides/shows Razorpay and Cashfree specific credential fields based on the selected Active Gateway using Javascript toggles.
- Handled localized SSL configuration (`CURLOPT_SSL_VERIFYPEER, false`) across the application to prevent `curl_error` blocks when testing APIs from environments without default SSL CA bundles (e.g. Laragon).
- Increased upload constraints and memory limit from standard configurations (5MB file support).

### Fixed
- Fixed an issue where the slider and CMS media images "Visible on Homepage" toggle logic failed to persist. Changed from `GET` endpoint architecture to a proper form `POST` submission in `media_index.php` and `web.php`.

## [1.0.6] - 2026-09-17

### Added
- Added "Check for Updates" and "Force Reinstall" manual action buttons to the System Updates dashboard.

## [1.0.5] - 2026-09-17

### Fixed
- Fixed an issue where slider and CMS media images were bypassing the WebP image compression engine on upload.

## [1.0.4] - 2026-09-17

### Fixed
- Removed frontend file size restrictions on image uploads in Organization Settings, CMS About, and CMS Media. The system will now correctly accept high-resolution images and automatically compress them using the backend WebP optimization engine.

## [1.0.3] - 2026-09-17

### Changed
- Replaced GitHub Releases implementation with a fully automated branch-tracking system.
- The System Updater now fetches the raw config file directly from the `main` branch to check for updates, eliminating the need to manually create Git Tags and GitHub Releases.

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
