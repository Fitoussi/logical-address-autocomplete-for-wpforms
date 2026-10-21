<?php
/**
 * Whitelisted saved autocomplete preferences.
 *
 * @package WPFormsGeolocationAutocomplete\Features\Form\Fields\Address
 */

namespace WPFormsGeolocationAutocomplete\Features\Form\Fields\Address;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retain legacy keys where equivalent; never export geocoder or locator options.
 */
final class Settings {

	/**
	 * Dedicated fields default to enabled; native Address fields are opt-in.
	 *
	 * @return array
	 */
	public static function defaults() {
		return [
			'geo_address_autocomplete'                 => '1',
			'geo_force_autocomplete_selection'         => '',
			'geo_force_autocomplete_selection_message' => __( 'Please select an address from the suggested results.', 'logical-address-autocomplete-for-wpforms' ),
			'geo_address_autocomplete_types'           => '',
			'geo_address_autocomplete_countries'       => '',
			'geo_address_autocomplete_language'        => '',
			'geo_autocomplete_restriction_usage'       => '',
			'geo_autocomplete_proximity_lat'           => '',
			'geo_autocomplete_proximity_lng'           => '',
			'geo_autocomplete_proximity_radius'        => '100',
			'geo_autocomplete_bounds_sw_point'         => '',
			'geo_autocomplete_bounds_ne_point'         => '',
			'geo_address_autocomplete_strict_bounds'   => '',
		];
	}

	/**
	 * Check explicit host checkbox values without coercing false-like strings.
	 *
	 * @param mixed $value Saved setting.
	 * @return bool
	 */
	public static function enabled( $value ) {
		return in_array( $value, [ true, 1, '1', 'true' ], true );
	}

	/**
	 * Avoid duplicate autocomplete when WPForms' official add-on owns this field.
	 *
	 * @param array $field Native field definition.
	 * @return bool
	 */
	public static function official_owns( $field ) {
		return 'address' === ( $field['type'] ?? '' ) && ! empty( $field['enable_address_autocomplete'] ) && ( class_exists( 'WPFormsGeolocation\Front\Fields' ) || defined( 'WPFORMS_GEOLOCATION_VERSION' ) );
	}

	/**
	 * Export only supported scalar values without modifying the stored form.
	 *
	 * @param array $field Native field definition.
	 * @return array
	 */
	public static function export( $field ) {
		$output = [];
		foreach ( self::defaults() as $key => $default ) {
			$value = $field[ $key ] ?? $default;
			if ( in_array( $key, [ 'geo_address_autocomplete_types', 'geo_address_autocomplete_countries' ], true ) && is_array( $value ) ) {
				$value = implode( ',', array_filter( $value, 'is_scalar' ) );
			}
			$output[ $key ] = is_scalar( $value ) ? sanitize_text_field( (string) $value ) : $default;
		}
		return $output;
	}

}
