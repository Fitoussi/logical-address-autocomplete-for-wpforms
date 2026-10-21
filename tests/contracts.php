<?php
/** Standalone PHP contracts; no installed WordPress or persistent writes. */
define( 'ABSPATH', __DIR__ );
function __( $text, $domain = '' ) { return $text; }
function esc_html__( $text, $domain = '' ) { return htmlspecialchars( $text ); }
function esc_html( $value ) { return htmlspecialchars( $value, ENT_QUOTES ); }
function esc_attr( $value ) { return htmlspecialchars( $value, ENT_QUOTES ); }
function absint( $value ) { return abs( (int) $value ); }
function checked( $actual, $expected = true, $echo = true ) { return $actual == $expected ? 'checked="checked"' : ''; }
function selected( $actual, $expected = true, $echo = true ) { return $actual == $expected ? 'selected="selected"' : ''; }
function sanitize_text_field( $value ) { return strip_tags( $value ); }
function get_option( $key, $default = [] ) { return $GLOBALS['options'][ $key ] ?? $default; }
function add_action( ...$args ) { $GLOBALS['hooks'][] = $args; }
function add_filter( ...$args ) { $GLOBALS['hooks'][] = $args; }
function apply_filters( $hook, $value, ...$args ) { return $value; }
function wpforms() { return new class { public function is_pro() { return $GLOBALS['paid'] ?? false; } }; }
function wp_enqueue_script( ...$args ) { $GLOBALS['scripts'][] = $args; }
function wp_enqueue_style( ...$args ) {}
function wp_add_inline_script( $handle, $script ) { $GLOBALS['payloads'][] = $script; }
function wp_json_encode( $data, $flags = 0 ) { return json_encode( $data, $flags ); }
function expect( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }
/**
 * Stub the host renderer; installed browser tests exercise WPForms' real helper.
 *
 * @param array  $args Control arguments.
 * @param string $id Input identifier.
 * @param string $name Saved field name.
 * @param string $label Escaped label.
 * @param string $value Checkbox value.
 * @param string $data_attr Host attributes.
 * @return string
 */
function wpforms_panel_field_toggle_control( $args, $id, $name, $label, $value, $data_attr ) {
	$tooltip = empty( $args['tooltip'] ) ? '' : '<i class="fa fa-question-circle-o wpforms-help-tooltip" title="' . esc_attr( $args['tooltip'] ) . '"></i>';
	return sprintf(
		'<span class="wpforms-toggle-control"><input type="checkbox" id="%s" name="%s" class="%s" value="1" %s><label class="wpforms-toggle-control-icon" for="%s"></label><label class="wpforms-toggle-control-label" for="%s">%s</label></span>',
		esc_attr( $id ),
		esc_attr( $name ),
		esc_attr( $args['input-class'] ),
		checked( true, (bool) $value, false ),
		esc_attr( $id ),
		esc_attr( $id ),
		$label
	) . $tooltip;
}
define( 'WPFRMGEOAC_URL', 'https://fixture.test/plugin/' );
define( 'WPFRMGEOAC_VERSION', '1.0.0' );
require __DIR__ . '/../src/Features/Form/Fields/Address/Settings.php';
require __DIR__ . '/../src/Admin/Settings.php';
require __DIR__ . '/../src/Features/Form/Runtime/FormGeoProcessor.php';
require __DIR__ . '/../src/Features/Form/Admin/FormEditor.php';
require __DIR__ . '/../src/Admin/Dashboard/Features.php';
require __DIR__ . '/../src/Helpers/ReferenceData.php';
use WPFormsGeolocationAutocomplete\Features\Form\Fields\Address\Settings as Schema;
use WPFormsGeolocationAutocomplete\Admin\Settings;
$GLOBALS['options']['wpforms_settings'] = [ 'wpfrmgeo-google-maps-browser-api-key' => 'browser', 'wpfrmgeo-google-maps-server-api-key' => 'NEVER-EXPOSE', 'wpfrmgeo-license-key' => 'LICENSE-SECRET' ];
expect( 4 === count( Settings::get_config() ), 'Only public browser configuration exported' );
expect( false === strpos( json_encode( Settings::get_config() ), 'SECRET' ), 'No private config leaked' );
$form = [ 'id' => 7, 'fields' => [ [ 'id' => 11, 'type' => 'wpfrmgeo_address', 'geo_geocoder_id' => 9, 'geo_ip_token' => 'PRIVATE' ], [ 'id' => 12, 'type' => 'address', 'geo_address_autocomplete' => '1' ], [ 'id' => 13, 'type' => 'address' ], [ 'id' => 14, 'type' => 'wpfrmgeo_map' ] ] ];
$before = serialize( $form );
$runtime = new WPFormsGeolocationAutocomplete\Features\Form\Runtime\FormGeoProcessor();
$runtime->collect( $form );
expect( false !== strpos( $GLOBALS['payloads'][0], '"11"' ) && false === strpos( $GLOBALS['payloads'][0], '"12"' ), 'Lite only exports dedicated field' );
$GLOBALS['paid'] = true;
$runtime->collect( $form );
expect( false !== strpos( $GLOBALS['payloads'][1], '"12"' ) && false === strpos( $GLOBALS['payloads'][1], '"13"' ), 'Native opt-in, not all native fields' );
expect( false === strpos( $GLOBALS['payloads'][1], 'PRIVATE' ) && false === strpos( $GLOBALS['payloads'][1], 'geo_geocoder_id' ), 'Paid preferences excluded' );
expect( $before === serialize( $form ), 'Input form immutable' );
expect( ! Schema::enabled( 'false' ) && Schema::enabled( '1' ), 'Explicit flags' );
$fields = ( new Settings() )->fields( [ 'unrelated' => [ 'secret' => 'preserved' ] ] );
expect( isset( $fields['unrelated'] ), 'Settings definition merge retains host tabs' );
expect( 4 === count( $fields['wpfrmgeo-autocomplete'] ), 'Three editable values plus heading only' );
$editor = new WPFormsGeolocationAutocomplete\Features\Form\Admin\FormEditor();
$groups = $editor->buttons( [ 'standard' => [ 'fields' => [] ], 'fancy' => [ 'fields' => [] ] ] );
expect( 4 === count( $groups['wpfrmgeo_geolocation']['fields'] ), 'Exactly four promotions' );
$metadata = [
 'wpfrmgeo_geolocation' => [ 'fields' => [ [ 'type' => 'wpfrmgeo_address', 'action' => 'license' ], [ 'type' => 'wpfrmgeoac_promo_map', 'action' => 'license' ], [ 'type' => 'third_party', 'action' => 'license' ] ] ],
 'fancy' => [ 'fields' => [ [ 'type' => 'address', 'action' => 'license' ] ] ],
];
$independent = $editor->free_buttons( $metadata );
expect( ! isset( $independent['wpfrmgeo_geolocation']['fields'][0]['action'] ) && ! isset( $independent['wpfrmgeo_geolocation']['fields'][1]['action'] ), 'Own free field and previews do not acquire host paid metadata' );
expect( 'license' === $independent['wpfrmgeo_geolocation']['fields'][2]['action'] && $metadata['fancy'] === $independent['fancy'], 'Third-party and native paid restrictions remain untouched' );
expect( $editor->free_buttons( [ 'standard' => [] ] ) === [ 'standard' => [] ], 'Missing custom group is unchanged' );
foreach ( $groups['wpfrmgeo_geolocation']['fields'] as $field ) {
 $atts = $editor->button_attributes( [], $field );
 expect( ! isset( $atts['data']['field-type'] ), 'Promotion cannot enter add-field pipeline' );
 expect( in_array( 'wpforms-add-fields-button', $atts['class'], true ) && in_array( 'not-draggable', $atts['class'], true ), 'Native appearance with native drag opt-out' );
}
define( 'WPFORMS_GEOLOCATION_VERSION', 'fixture' );
$form['fields'][1]['enable_address_autocomplete'] = 1;
$runtime->collect( $form );
expect( false === strpos( $GLOBALS['payloads'][2], '"12"' ), 'Official addon owns enabled native field' );
$feature_source = file_get_contents( __DIR__ . '/../src/Admin/Dashboard/Features.php' );
expect( false === strpos( $feature_source, "'agency' =>" ), 'No invented tier' );
foreach ( [ 'native-address', 'field-language' ] as $feature_id ) {
 $feature = WPFormsGeolocationAutocomplete\Admin\Dashboard\Features::get_features()[ $feature_id ];
 expect( [ 'free', 'premium' ] === $feature['packages'] && $feature['included'], 'Both current packages include Address component filling and field language' );
}
$template = file_get_contents( __DIR__ . '/../src/Admin/Dashboard/views/dashboard.php' );
expect( false === strpos( $template, 'Check version' ), 'Comparison uses checkmarks rather than version labels' );
expect( strpos( $template, '</nav>' ) < strpos( $template, '<hr class="wp-header-end">' ), 'Notices below navigation' );
expect( false === strpos( $template, 'Screenshot placeholder' ), 'No invented screenshots' );
$payload_count = count( $GLOBALS['payloads'] );
$runtime->collect( [ 'fields' => $form['fields'] ] );
$runtime->collect( [ 'id' => 8, 'fields' => [ [ 'type' => 'wpfrmgeo_address' ] ] ] );
expect( $payload_count === count( $GLOBALS['payloads'] ), 'Malformed form or field identities are ignored safely' );
foreach ( [ '', '1' ] as $enabled ) {
 foreach ( [ '', '1' ] as $force ) {
  foreach ( [ '', 'proximity', 'area_bounds' ] as $bias ) {
   $definition = [ 'id' => 51, 'type' => 'wpfrmgeo_address', 'geo_address_autocomplete' => $enabled, 'geo_force_autocomplete_selection' => $force, 'geo_autocomplete_restriction_usage' => $bias, 'geo_force_autocomplete_selection_message' => 'Keep "my" alert', 'geo_autocomplete_proximity_lat' => '40.123', 'geo_autocomplete_bounds_sw_point' => '30,-80', 'geo_address_autocomplete_countries' => 'US,CA' ];
   $saved_definition = serialize( $definition );
   ob_start();
   $editor->options( $definition );
   $markup = ob_get_clean();
   $dom = new DOMDocument();
   $previous_errors = libxml_use_internal_errors( true );
   $dom->loadHTML( '<?xml encoding="utf-8" ?><main>' . $markup . '</main>' );
   libxml_clear_errors();
   libxml_use_internal_errors( $previous_errors );
   $xpath = new DOMXPath( $dom );
   $body = $xpath->query( '//*[contains(@class,"wpfrmgeoac-enabled-options")]' )->item( 0 );
   expect( 'fields[51][geo_address_autocomplete]' === $body->getAttribute( 'data-toggle' ), 'Native master toggle scoped by field name' );
   expect( ( '' === $enabled ) === ( 'display:none' === $body->getAttribute( 'style' ) ), 'Master group has correct initial visibility' );
   foreach ( [ 'geo_force_autocomplete_selection_message' => '1' === $force, 'geo_autocomplete_proximity_lat' => 'proximity' === $bias, 'geo_autocomplete_bounds_sw_point' => 'area_bounds' === $bias, 'geo_address_autocomplete_strict_bounds' => 'area_bounds' === $bias ] as $key => $visible ) {
    $row = $xpath->query( '//*[@data-wpfrmgeoac-option="' . $key . '"]' )->item( 0 );
    expect( ! $visible === ( 'display:none' === $row->getAttribute( 'style' ) ), 'Native dependent row initial visibility: ' . $key );
    expect( false !== strpos( $row->getAttribute( 'class' ), 'wpforms-panel-field-toggle-body' ), 'Use host dependent-row class' );
   }
   expect( 3 === $xpath->query( '//*[contains(concat(" ",normalize-space(@class)," ")," wpforms-panel-field-toggle ")]' )->length, 'Three native controller inputs' );
   expect( 3 === $xpath->query( '//*[contains(concat(" ",normalize-space(@class)," ")," wpforms-toggle-control ")]' )->length, 'Three switches use the native WPForms renderer' );
   expect( 0 === $xpath->query( '//*[@disabled]' )->length, 'Hiding never disables saved-value inputs' );
   expect( false === stripos( $markup, 'Geocoder' ), 'Editor help does not expose an unrelated Geocoder concept' );
   foreach ( [ 'geo_force_autocomplete_selection' => 'Require visitors to select an address from the suggestions.', 'geo_autocomplete_restriction_usage' => 'Prioritize suggestions near a specified location.', 'geo_address_autocomplete_types' => 'Choose up to 5 place types, or leave empty for all types.' ] as $key => $help ) {
    $icon = $xpath->query( '//*[@data-wpfrmgeoac-option="' . $key . '"]//*[contains(@class,"wpforms-help-tooltip")]' )->item( 0 );
    expect( $icon && $help === $icon->getAttribute( 'title' ), 'Existing description is a native tooltip: ' . $key );
   }
   expect( 0 === $xpath->query( '//p' )->length, 'No persistent option descriptions' );
   expect( 1 === $xpath->query( '//*[contains(@class,"autocomplete-type-collection-note") and @hidden]' )->length, 'Only the conditional collection warning remains beneath an input' );
   expect( 'Keep "my" alert' === $dom->getElementById( 'wpforms-field-option-51-geo_force_autocomplete_selection_message' )->getAttribute( 'value' ), 'Hidden message preserved and escaped' );
   expect( '40.123' === $dom->getElementById( 'wpforms-field-option-51-geo_autocomplete_proximity_lat' )->getAttribute( 'value' ), 'Hidden proximity preserved' );
   expect( '30,-80' === $dom->getElementById( 'wpforms-field-option-51-geo_autocomplete_bounds_sw_point' )->getAttribute( 'value' ), 'Hidden bounds preserved' );
   expect( $saved_definition === serialize( $definition ), 'Rendering dependencies does not mutate saved values' );
  }
 }
}
echo "PHP contracts passed\n";
