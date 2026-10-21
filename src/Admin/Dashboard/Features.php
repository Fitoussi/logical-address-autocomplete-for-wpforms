<?php
/**
 * Local descriptions of free and premium tools.
 *
 * @package WPFormsGeolocationAutocomplete\Admin\Dashboard
 */

namespace WPFormsGeolocationAutocomplete\Admin\Dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Presentation only, not a remote catalog or entitlement gate.
 */
final class Features {

	/**
	 * Keep a deliberate feature order without reordering caller data.
	 *
	 * @param array $features Local definitions.
	 * @return array
	 */
	public static function order_for_overview( $features ) {
		return $features;
	}

	/**
	 * No invented feature-specific demos.
	 *
	 * @param string $feature_id Feature identity.
	 * @return array
	 */
	public static function get_demo_links( $feature_id ) {
		$demos = [];
		return $demos[ $feature_id ] ?? [];
	}

	/**
	 * Current premium is a single product, without invented package tiers.
	 *
	 * @return array
	 */
	public static function get_packages() {
		return [
			'free'    => __( 'Free', 'logical-address-autocomplete-for-wpforms' ),
			'premium' => __( 'Geolocation for WPForms', 'logical-address-autocomplete-for-wpforms' ),
		];
	}

	/**
	 * Describe actual features and known upgrade differences.
	 *
	 * @return array
	 */
	public static function get_features() {
		$items  = [
			'autocomplete'     => [ __( 'Address autocomplete', 'logical-address-autocomplete-for-wpforms' ), __( 'Google Places suggestions in a dedicated single-line Address field.', 'logical-address-autocomplete-for-wpforms' ), __( 'Country, result-type and bounds controls, global region and language, and optional required suggestion selection.', 'logical-address-autocomplete-for-wpforms' ), true, [ 'free', 'premium' ], 'location-alt' ],
			'native-address'   => [ __( 'Native Address component filling', 'logical-address-autocomplete-for-wpforms' ), __( 'Populate street, city, state, postal code and country directly from a selected suggestion.', 'logical-address-autocomplete-for-wpforms' ), __( 'Requires a WPForms edition providing the native Address field. Older premium releases require a connected Geocoder for this workflow; check your premium version.', 'logical-address-autocomplete-for-wpforms' ), true, [ 'free', 'premium' ], 'editor-table' ],
			'field-language'   => [ __( 'Per-field suggestion language', 'logical-address-autocomplete-for-wpforms' ), __( 'Choose a different language for individual Address fields.', 'logical-address-autocomplete-for-wpforms' ), __( 'Premium support depends on the version. Older releases use the global language setting instead.', 'logical-address-autocomplete-for-wpforms' ), true, [ 'free', 'premium' ], 'translation' ],
			'location-map'     => [ __( 'Interactive form maps', 'logical-address-autocomplete-for-wpforms' ), __( 'Display and select locations on connected maps.', 'logical-address-autocomplete-for-wpforms' ), __( 'Available in Geolocation for WPForms.', 'logical-address-autocomplete-for-wpforms' ), false, [ 'premium' ], 'location' ],
			'geocoder'         => [ __( 'Geocoder and connected location fields', 'logical-address-autocomplete-for-wpforms' ), __( 'Connect addresses, coordinates, maps and dynamic field values.', 'logical-address-autocomplete-for-wpforms' ), __( 'Requires premium configuration and the relevant Google services.', 'logical-address-autocomplete-for-wpforms' ), false, [ 'premium' ], 'database' ],
			'current-location' => [ __( 'Locator and Reset controls', 'logical-address-autocomplete-for-wpforms' ), __( 'Request the visitor’s location or clear a connected location.', 'logical-address-autocomplete-for-wpforms' ), __( 'Browser location requires permission; optional IP location requires a provider.', 'logical-address-autocomplete-for-wpforms' ), false, [ 'premium' ], 'admin-site-alt3' ],
			'coordinates'      => [ __( 'Coordinate inputs', 'logical-address-autocomplete-for-wpforms' ), __( 'Collect latitude and longitude in your forms.', 'logical-address-autocomplete-for-wpforms' ), __( 'A dedicated premium field.', 'logical-address-autocomplete-for-wpforms' ), false, [ 'premium' ], 'admin-generic' ],
			'directions'       => [ __( 'Directions and travel calculations', 'logical-address-autocomplete-for-wpforms' ), __( 'Connect an origin and destination for directions, distance and duration.', 'logical-address-autocomplete-for-wpforms' ), __( 'Current premium provides Directions and Directions Panel fields.', 'logical-address-autocomplete-for-wpforms' ), false, [ 'premium' ], 'leftright' ],
			'entry-maps'       => [ __( 'Mashup maps', 'logical-address-autocomplete-for-wpforms' ), __( 'Display saved entry locations together on a map.', 'logical-address-autocomplete-for-wpforms' ), __( 'Requires paid WPForms entry storage and premium geolocation data.', 'logical-address-autocomplete-for-wpforms' ), false, [ 'premium' ], 'admin-multisite' ],
		];
		$output = [];
		foreach ( $items as $id => $item ) {
			$output[ $id ] = [
				'title'       => $item[0],
				'description' => $item[1],
				'details'     => $item[2],
				'included'    => $item[3],
				'packages'    => $item[4],
				'icon'        => $item[5],
				'doc'         => '',
			];
		}
		return $output;
	}

}
