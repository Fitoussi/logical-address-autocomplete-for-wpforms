# Standalone WPForms autocomplete guidance

Read docs/README.md before changes. Preserve existing working tree changes.

- Isolated WPFRMGEOAC constants and WPFormsGeolocationAutocomplete namespace.
- Preserve wpfrmgeo_address and equivalent geo_* / wpfrmgeo-* saved keys.
- Premium owns overlapping fields in either plugin load order, including its
  late init version constant. Detect its bootstrap class before registration.
- Dedicated Address works in Lite and paid WPForms. Native Address is opt-in
  and only available when provided by paid WPForms. Fill components from Places
  directly, without Geocoder, coordinates, locator or other premium services.
- Never double-initialize a native field handled by the official Geolocation
  add-on. Preserve its settings and all other host preferences.
- Four discovery-only editor controls: Map, Directions, Coordinates, Locator
  Button. No promotional field classes, AJAX insertion or unsupported persistence.
- No framework, licensing SDK, Connect, account, remote feed or migrations.
- Dashboard content is local. Show known premium compatibility differences;
  do not invent tiers, features, screenshots, demos or compatibility claims.
- Multiline PHPDoc and ABSPATH guards, tabs, short arrays, spaced members.
- npm ci, npm run build, npm test; PHP 7.4 lint; tools/package/build.php.
- Run installed/browser acceptance and Plugin Check on the final ZIP. Preserve
  unrelated Test Site 2 forms, entries, settings and plugin activation states.
- Public repository creation does not authorize source push, tag, release or
  WordPress.org publication. Stop for owner review before publishing.
