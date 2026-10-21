# Logical Address Autocomplete for WPForms

Submission-ready 1.0.0 baseline. The owner authorized the initial Git commit and
source push on October 20, 2026. GitHub tags/releases are deferred until the
WordPress.org review outcome. No WordPress.org submission or SVN publication
is authorized by that source-push approval.

Development repository:
https://github.com/Fitoussi/logical-address-autocomplete-for-wpforms

## Layout

- Entry point: isolated constants/autoloading and deferred ownership checks.
- src/Loader.php: dependency check and premium-wins lifecycle.
- src/Admin/Settings.php: three native WPForms browser preferences only.
- src/Admin/SetupNotice.php: one-time dismissible setup guidance; no redirects.
- src/Admin/Dashboard/: local copyable Overview, Compare Packages, Help and
  More Plugins module, adapted from the approved Formidable dashboard.
- src/Features/Form/Fields/Address/: native Text subclass and option allowlist.
- src/Features/Form/Admin/: native Address Autocomplete option-group tab after
  Advanced and faded discovery-only buttons. Only this plugin's free identifiers
  are kept independent of host paid-field education metadata. Dependent options
  use WPForms' native panel-field toggles; hiding never clears their saved values.
  Field-option guidance uses native WPForms help tooltips. Only the conditional
  Cities/Regions selection warning remains beneath its input.
- src/Features/Form/Runtime/: immutable whitelisted render payload.
- assets/js/autocomplete/: existing modern Places service and isolated legacy
  fallback, plus WPForms lifecycle/native-component adapters.
- tests/: PHP contracts and actual built-bundle DOM acceptance fixtures.
- assets/screenshots/: numbered directory screenshots; matching captions are in
  readme.txt and the source mapping is in docs/screenshots.txt.
- tools/: isolated PHP coding/compatibility tools and explicit package builder.

## Compatibility contracts

Dedicated field type wpfrmgeo_address; equivalent geo_* field options and
wpfrmgeo-google-maps-browser-api-key / wpfrmgeo-default-region /
wpfrmgeo-default-language inside wpforms_settings remain compatible.

No geocoder, locator, coordinates, directions, licensing or premium runtime is
bundled. No credentials other than the intended browser API key are exported.
Native Address needs a paid host edition. WPForms' official Geolocation
autocomplete, if enabled for a native field, takes precedence for that field.

Premium's version constant is defined on init. Its global bootstrap class is
therefore checked after all plugin files load, before free registers hooks.
Free does not auto-deactivate, redirect, delete data or change premium settings.
No premium code is bundled or modified by this free plugin.

Premium compatibility depends on the installed version. Older released versions
have different autocomplete behavior and require a connected Geocoder for native
component filling. A separate, unreleased premium candidate passed the forward
upgrade checks recorded in the acceptance report. That result is not a promise
about older public releases. These internal compatibility limits remain relevant;
at the owner's request, the public readme uses the simple sibling-plugin copy
without the additional legacy-version warning paragraphs.

No Requires Plugins: wpforms-lite header is declared because it would force Lite
onto paid-only installations. The runtime guard accepts either edition.

## Development and verification

npm ci; npm run build; npm test. npm run package creates an immutable versioned
ZIP in dist/. Build tools, tests, dependencies and private docs do not ship.
Readable JS, SCSS, sourcemaps, license notices and translations do ship.
Quality tooling: composer install --working-dir=tools; composer phpcs and
composer phpcompat from tools. No production Composer dependency is required.

Test with Lite and paid WPForms: field insertion and option persistence, native
US/international Address filling, keyboard suggestions, required selection and
valid retry, repeated renders, duplicate embeds, hidden/conditional fields,
editor promo mouse/keyboard behavior, dashboard navigation, native settings,
submission/notification values, premium coexistence in both orders, and official
Geolocation ownership. Do not label fixtures as browser/installed evidence.

Initial local baseline: sibling Formidable main commit
932a4cff97d8ada842effb112f5a26b89a42fcd5. Shared runtime source is copied, not linked.
Test reports and host versions belong in docs/acceptance.md before handoff.

Canonical source lives in gmw-dev/wp-content/plugins/
logical-address-autocomplete-for-wpforms. Test Site 2 uses a separate installed
ZIP, not a link to the development repository.
