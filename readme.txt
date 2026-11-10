=== Logical Address Autocomplete for WPForms ===
Contributors: ninjew
Tags: wpforms, address autocomplete, google places, address, geolocation
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Google Places address suggestions for WPForms Lite and paid WPForms, with a dedicated Address field and optional native Address component filling.

== Description ==

Add Google-powered address suggestions to WPForms with a dedicated single-line Address field or optional native Address autofill. Visitors start typing, choose a suggestion, and continue filling out the form with less typing.

= Features =

* Dedicated single-line Address field in the native WPForms builder, available with Lite and paid WPForms.
* Optional autofill for native Address fields in paid WPForms editions that provide them.
* Country restrictions: up to five countries per field.
* Result types: choose up to five supported place types, or use Cities or Regions on its own.
* Per-field suggestion language.
* Global language and region preferences.
* Location bias by point and radius or rectangular bounds.
* Optional restriction to rectangular bounds.
* Required suggestion selection with a custom alert message.
* Saves addresses through WPForms' normal submission lifecycle; entry storage depends on your WPForms edition.

= Address fields for your forms =

Add the dedicated Address field from the Geolocation field group and configure autocomplete in its field settings. This single-line field works with the free WPForms plugin.

If your WPForms edition supplies a native multipart Address field, enable autocomplete in its options to fill street address, city, state, postal code and country. Google may not return every component for every suggestion.

= Customize suggestions =

Choose countries, result types and language for each field. Prioritize suggestions near a point or within map bounds, or restrict results to those bounds. The global region preference influences results; country restrictions limit which countries can appear.

= Require a suggestion selection =

Optionally require visitors to choose an address suggestion. Manually typed, unselected text is cleared, and your custom alert prompts them to select a suggestion. This is not postal address validation or a deliverability guarantee.

= Google API requirements =

A Google Maps API key is required for address autocomplete to work. Follow [this tutorial](https://wpgeoforms.com/docs/google-maps-api-keys-wpforms-geolocation/) to create your Google Maps API key.

[Google Maps Platform terms](https://cloud.google.com/maps-platform/terms) | [Google privacy policy](https://policies.google.com/privacy)

= Need more than autocomplete? =

[Geolocation for WPForms](https://wpgeoforms.com/) offers interactive maps, geocoding, coordinates, current-location detection, directions and travel calculations, dynamic location fields and mashup maps.

Geolocation for WPForms is a separate plugin, not functionality unlocked inside this free plugin by entering a license.

[Explore packages and pricing](https://wpgeoforms.com/pricing/) | [Try the live demos](https://demo.wpgeoforms.com/) | [Read the documentation](https://wpgeoforms.com/docs/)

== Installation ==

1. Install and activate WPForms Lite or paid WPForms 1.9.4.1 or newer.
2. Install and activate Logical Address Autocomplete for WPForms. Deactivate Geolocation for WPForms first if you are switching to this free plugin.
3. Open WPForms > Settings > Address Autocomplete.
4. Enter your browser API key with Maps JavaScript API and Places API (New) enabled, billing configured and website restrictions applied.
5. Set optional global region and language preferences.
6. Add Address from the Geolocation group, or enable autocomplete on a native Address field if your paid WPForms edition provides it. Configure the Address Autocomplete tab after Advanced and save.
7. Preview and test selecting suggestions and submitting the form.

The overview is available under WPForms > Address Autocomplete. If WPForms is unavailable, it appears under Settings > Address Autocomplete instead.

== Frequently Asked Questions ==

= Which fields support address autocomplete? =

You can enable address autocomplete on the plugin's dedicated single-line Address field in WPForms Lite or paid WPForms. You can also enable it on WPForms' native multi-part Address field with a paid edition that provides it.

= Are all the autocomplete controls included free? =

Yes! All the address autocomplete controls described above are included in the free plugin.

= Can I customize the address suggestions? =

Yes! You can limit suggestions to selected countries, choose the types of places to suggest, and prioritize suggestions around a location or area. You can also set a default language and choose a different language for individual fields.

= Can I require visitors to select an address suggestion? =

Yes! You can require visitors to choose an address from the suggestions and customize the message that guides them to make a selection. Visitors can retry after unselected text is cleared, or enter addresses manually when this option is off.

= Can I use it on AJAX or multi-page forms? =

The plugin supports WPForms rendering and dynamically displayed autocomplete fields. Multi-page, conditional and repeater forms use the features available in your WPForms edition.

= How do I switch to the premium plugin? =

Deactivate the free plugin when switching to [Geolocation for WPForms](https://wpgeoforms.com/pricing/). Your saved fields and settings stay in place. If both are active, the free plugin pauses its overlapping fields and integrations.

= Can I also use WPForms' official Geolocation add-on? =

Yes! You can use the official add-on for native Address fields. This plugin leaves fields with the official add-on's autocomplete enabled to that add-on. Check that the two plugins' Google API settings work together on your site.

= Do I need a server API key? =

You can use your Google Maps browser API key. Enable billing and restrict the key to your website.

= Is a plugin account or license required? =

You can use the plugin without creating a plugin account or buying a license. You'll need your own Google Cloud account and browser API key for Google's address suggestions.

= Where can developers find the source code? =

Readable JavaScript and SCSS sources are included in the plugin. The full development project, build configuration and dependency lockfile are available on [GitHub](https://github.com/Fitoussi/logical-address-autocomplete-for-wpforms). To rebuild assets, download the repository, install Node.js/npm, then run `npm ci` and `npm run build` (esbuild and Sass). These tools are not required to use the plugin.

Includes [Choices.js 11.1.0](https://github.com/Choices-js/Choices/tree/v11.1.0), MIT licensed; readable distribution: assets/js/editor/vendor/choices.js; license: assets/licenses/choices.txt.

== External services ==

Google Maps JavaScript API and Google Places provide address suggestions and selected-place details. On pages with enabled autocomplete fields, the browser connects to Google using the configured browser API key. Search text and request context such as configured language, region and location bias are sent when the visitor types; selected-place details are requested after selection. No request is made to Google merely by visiting this plugin's dashboard. This plugin does not request device location or send server credentials.

Google terms: https://cloud.google.com/maps-platform/terms
Google privacy policy: https://policies.google.com/privacy

== Screenshots ==

1. Google Places address suggestions while typing in the dedicated Address field. Works with WPForms Lite and paid WPForms.
2. Street, city, state and ZIP code filled from a selected suggestion in the native Address field. Requires a paid WPForms edition providing that field.
3. Dedicated Address field options, including required suggestion selection, result types, countries, language and location bias.
4. Autocomplete options for the native Address field, with multiple place-type and country filters. Requires a paid WPForms edition providing that field.
5. Browser API key, region and language settings. The API key shown is a placeholder, not a credential.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
