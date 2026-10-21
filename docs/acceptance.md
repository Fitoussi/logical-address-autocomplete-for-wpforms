# WPForms free 1.0.0 submission acceptance

Date: October 20, 2026. Status: final submission ZIP verified. The owner authorized
the initial source commit and push; GitHub tags/releases are deferred until the
WordPress.org review outcome. No WordPress.org submission, SVN upload or production
deployment is included in that authorization. Five owner-approved screenshots
are bundled; purple banners and matching icons are in docs/wordpress-org-assets/.

## Final submission baseline

ZIP: logical-address-autocomplete-for-wpforms.1.0.0.zip, with an identical
unversioned upload copy in the workspace artifact folder's readme-exact/ directory.
SHA-256: 2245a321241d72fd4a992ccaf0308f67ff4b0e496cb079f9006a981fe47b11db.
All 43 files match canonical source. The owner-specified four description
sections exactly match the Formidable readme with WPForms name/URL substitutions.
Changelog: Initial release.

Plugin Check 2.1.0 was rerun on the exact archive's extracted files, without
suppression: zero errors and three trademarked_term warnings about wp within
WPForms in the readme title, PHP title and plugin slug. The successful run
followed correction of the local CLI bootstrap, not a plugin runtime change.
Activation state was unchanged and the checker-owned temporary drop-in removed.
Earlier Lite/paid browser acceptance and all 28 JS tests remain applicable to
the unchanged runtime. Build dependencies and lockfiles are unchanged.

The prior preparation records below are historical snapshots, not the current
archive selection. Source-only publication does not alter the verified ZIP.

## Previous owner-requested simple sibling readme

The latest submission archive is in `readme-simple/`, with SHA-256
`778c536ac1e9b3e91865b45f1be634fa8c69d3342224dc361a4fad75f1dc074a`.
At the owner's request, the public copy now follows the concise Formidable
example: premium summary, separate-plugin sentence and links, without the extra
version-compatibility/disclaimer paragraphs or runtime explanations. Remaining
sections and FAQs were shortened too. The Google disclosure remains brief;
WPForms native-field requirements remain accurate. Changelog: Initial release.
Only readme.txt differs from readme-aligned; all 43 files remain. Archive integrity
passes, identical versioned/unversioned ZIPs are retained, and runtime is unchanged.
No new live test or publishing is claimed for this text-only change.

## Previous sibling-structured readme update

The latest submission archive is now in the workspace artifact subfolder
`readme-aligned/`, with SHA-256
`78cb1c39d65767e3b658a23bfd213df13ae196d7794e8ea4a81d1d213b7392cb`.
The readme follows the sibling free autocomplete structure with fuller feature,
usage, suggestion customization, selection, API setup, premium links and FAQs.
WPForms-specific limits and external-service disclosure remain intact; the
changelog still says only Initial release. The five captions are unchanged.
Only readme.txt differs from changelog-final; all 43 files remain. ZIP integrity,
PHP contracts, both premium bootstrap orders and all 28 JS tests pass. Runtime
is unchanged; no fresh live install or Plugin Check is claimed for this copy-only
update. Identical versioned/unversioned ZIPs are retained. Nothing published.
The developer FAQ describes bundled readable sources/build steps without claiming
that the still-empty public GitHub repository already contains build files.

## Previous changelog-only update

The latest submission archive is in the workspace artifact subfolder
`changelog-final/`, with SHA-256
`997023f8a6f26f8931ff114a17ae827c960219c241752704fa5fde797497aa21`.
Both readme.txt and change_log.txt now say only Initial release for 1.0.0,
as requested by the owner. Archive integrity passes; comparison against
screenshots-complete confirms these are the only two changed files, with all
43 files retained. Runtime and screenshots are unchanged from earlier checks.
No repeat live installation or publishing was performed for this text-only
update. Identical versioned and unversioned archives are retained together.

## Screenshot packaging update

The latest unpublished archive is now in the workspace artifact subfolder
`screenshots-complete/`, with SHA-256
`009549e5f9c267b4149be0a60a399cc99eaddd57c06e851472e81655e4bd6315`.
It contains 43 files: the same tested runtime below, five numbered screenshots
and an updated readme with matching captions. Archive comparison confirms that
readme.txt is the only changed existing packaged file. All five PNGs decode.
PHP contracts and 28/28 JS tests were rerun successfully. The screenshot-only
archive has not been deployed to Test Site 2; its prior tested runtime remains
installed. The Settings screenshot uses a placeholder API key. No unredacted
Settings image was copied. Source mapping is in docs/screenshots.txt.

## Tested runtime artifact

- ZIP: `logical-address-autocomplete-for-wpforms.1.0.0.zip`
- SHA-256: `a4e9220e5251b35bb101a3ba44fede7fe3e4700836d6b9912a5ebe75eb71e71f`
- 38 runtime files; each matches both canonical source and Test Site 2.
- An identical unversioned ZIP is alongside the immutable versioned archive.
- Excludes development dependencies, tools, tests, internal docs, Git and QA data.
- Canonical Git is still unborn `main`; public repository remains empty.
- Source/tool lockfiles were retained; no dependency upgrade was performed.

Full evidence and backups are in the workspace artifact folder
`artifacts/wpforms-free-submission-1.0.0-20261020/`. Its `canonical-before.zip`
also preserves the previous historical acceptance document. This report
supersedes that document's old unresolved-upgrade and pending-toggle statements.
The current ZIP is in `native-tooltips/`; it supersedes the earlier ZIPs in
`checked-comparison/` and `submission/`, retained unchanged for rollback.

## Focused submission changes

- Public readme/changelog describe the initial release, not an internal candidate.
- At the owner's request, native Address filling and per-field language show
  included checkmarks in the premium comparison column. The introduction says
  the comparison describes the latest premium implementation; older releases
  may differ. Compatibility wording elsewhere remains version-qualified.
- Simplified the native component description and browser-key FAQ.
- Updated the conflict notice's compatibility wording; ownership is unchanged.
- Refreshed the POT from current PHP strings and updated the internal guide.
- Moved field-option descriptions into native WPForms help tooltips. Added brief
  clarification for per-field/global language and strict bounds; no unnecessary
  help was added for self-explanatory options. Official add-on ownership guidance
  is attached to Enable autocomplete when applicable. Only the conditional red
  Cities/Regions warning remains under an input. Existing identifiers, native
  dependency wrappers, comma-list values and autocomplete behavior are unchanged.
- Autocomplete runtime, editor behavior, field identifiers and saved keys are
  unchanged by this submission-copy pass.

## Automated checks

- Locked `npm ci --ignore-scripts`, production asset build and 28/28 JS tests pass.
- PHP contracts pass, including comparison metadata, notice anchor after nav,
  hidden dependency values, safe render payloads and both premium load orders.
- Full runtime WordPress coding standards and PHP 7.4+ compatibility checks pass.
- All runtime PHP parses with PHP 7.4; archive integrity and allowlist checks pass.
- Plugin Check 2.1.0 on the installed final ZIP: 0 errors, 3 unsuppressed
  `trademarked_term` warnings concerning `wp` within WPForms in the name/slug.
  This is not a WordPress.org approval or a guarantee of review acceptance.
- npm audit reported 12 development dependency advisories (1 moderate, 10 high,
  1 critical). They concern the local build/watch/test tree, excluded from the
  ZIP. No development server was started and dependency upgrades were not folded
  into this content-only preparation task.

## Installed browser checks

Test Site 2: WordPress 7.1.3, PHP 8.3; paid WPForms 1.9.4.1 and Lite 2.0.2.2
were tested separately with the exact final free ZIP. Existing host license
status was left unchanged.

- Paid: real Places predictions/selection in custom, native US and international
  Address fields; native components filled and Unit 7 retained. Actual submit
  confirmation passed; entry 26 stores the original field IDs and component data.
- Lite only: native Address class absent, dedicated field still works, real
  Places selection and submission confirmation passed. Lite does not store a
  local paid entry, as expected.
- Both hosts: native autocomplete controls, dependent option visibility and
  saved hidden values checked; disabled master state survived save/reload.
- Both hosts: all four faded premium preview controls opened local dialogs;
  no promotional field was inserted. Actual free Address insertion worked
  without invoking WPForms licensing. Unsaved insertion probes were discarded.
- Paid native Address exposes the dedicated Address Autocomplete option tab.
- Updated comparison rendered both premium cells as included checkmarks. Dashboard section
  links and the notice marker below nav were checked. Connect's separately
  installed older layout was inspected but not changed.

QA forms 549 (paid/custom + native) and 551 (Lite/custom) are retained, notification
disabled and not embedded on public pages. Their authenticated preview URLs
remain usable. Autocomplete is re-enabled and location bias disabled for review;
their hidden example values remain saved.

## Preservation and final state

`preservation.json` confirms all 25 original form fingerprints, all 25 existing
entry fingerprints, all 72 tracked settings and all 25 unrelated form-plugin
table fingerprints unchanged. The exact original activation array is restored:
paid WPForms and free autocomplete active; premium, Lite and Plugin Check
inactive. The tested submission ZIP remains installed. No temporary MU hook was
installed in this task.

## Earlier evidence and limits

The earlier `artifacts/wpforms-final-upgrade-regression-20261020/acceptance.json`
records broader runtime checks and a successful forward upgrade with the separate
unreleased premium candidate. It does not prove parity with older released
premium versions. This task did not change premium or repeat that upgrade.

Official WPForms Geolocation coexistence is covered by ownership contracts, not
an installed add-on test: the add-on is not configured on this site. No physical
GPS, IP-provider request or new API key configuration was attempted. Earlier
modern/legacy provider and keyboard/lifecycle regressions remain covered by the
unchanged runtime and the 28 current JS tests, not claimed as fresh live legacy
Cloud-configuration tests in this submission-copy pass.
