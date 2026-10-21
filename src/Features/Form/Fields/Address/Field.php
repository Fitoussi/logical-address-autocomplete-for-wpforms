<?php
/**
 * Real single-line Address field using WPForms' native text processing.
 *
 * @package WPFormsGeolocationAutocomplete\Features\Form\Fields\Address
 */

namespace WPFormsGeolocationAutocomplete\Features\Form\Fields\Address;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Preserve the premium saved field type without importing its runtime.
 */
final class Field extends \WPForms_Field_Text {

	/**
	 * Register the field identity and default settings.
	 *
	 * @return void
	 */
	public function init() {
		$this->name  = esc_html__( 'Address', 'logical-address-autocomplete-for-wpforms' );
		$this->type  = 'wpfrmgeo_address';
		$this->icon  = 'fa-map-marker';
		$this->order = 1;
		$this->group = 'wpfrmgeo_geolocation';
		add_filter( 'wpforms_field_new_default', [ $this, 'address_defaults' ] );
	}

	/**
	 * Add only supported settings to newly created Address fields.
	 *
	 * @param array $field Native field definition.
	 * @return array
	 */
	public function address_defaults( $field ) {
		return 'wpfrmgeo_address' === ( $field['type'] ?? '' ) ? array_merge( Settings::defaults(), $field ) : $field;
	}

}
