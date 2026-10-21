# Autocomplete place-type snapshot

Bundled locally from Google's Tables A and B on October 19, 2026:
https://developers.google.com/maps/documentation/javascript/place-types

514 ordinary types plus the exclusive Cities and Regions collections.
The JSON is reference data, not downloaded executable code or a runtime dependency.
No framework files or unrelated APIs are included.

The latest user-added type wins when switching to or from a collection. Ordinary
types remain limited to five. Loading fields and saving unrelated settings must
not rewrite older combinations, aliases or unknown values. The frontend runtime
guard remains unchanged. Existing invalid combinations are retained until edited.

The default description is “Choose up to 5 place types, or leave empty for all
types.” A separate red collection note is shown only while Cities or Regions is
selected, including when an existing configuration is loaded. Its visibility
does not trigger a setting save. Gravity's description gap is scoped to 6 px.

Review-only candidates were installed and exercised on Test Site 2 on October
19, 2026. The native editor checks cover collection/ordinary switching, saved
mixed/unknown values, five-type limits, and conditional help. WPForms was checked
with paid and Lite editions. All local PHP/JavaScript suites passed. See the
workspace autocomplete-place-types-20261019 artifact report and candidate manifest
for exact ZIP hashes, installed/source parity, data-preservation checks and scope.
No release version bump, commit, push, tag, SVN or production publication is part
of this change.
