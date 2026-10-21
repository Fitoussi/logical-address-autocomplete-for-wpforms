<?php
/**
 * Browser preferences on WPForms' native settings screen.
 *
 * @package WPFormsGeolocationAutocomplete\Admin
 */

namespace WPFormsGeolocationAutocomplete\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Let WPForms own authorization, nonce validation and narrow settings persistence.
 */
final class Settings {

	/**
	 * Register the native tab and its browser-only fields.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'wpforms_settings_tabs', [ $this, 'tabs' ] );
		add_filter( 'wpforms_settings_defaults', [ $this, 'fields' ] );
	}

	/**
	 * Add a tab separate from WPForms' official Geolocation add-on.
	 *
	 * @param array $tabs Existing tabs.
	 * @return array
	 */
	public function tabs( $tabs ) {
		$tabs['wpfrmgeo-autocomplete'] = [
			'name'   => __( 'Address Autocomplete', 'logical-address-autocomplete-for-wpforms' ),
			'form'   => true,
			'submit' => 'save',
		];
		return $tabs;
	}

	/**
	 * Only these three compatible keys may be edited by this tab.
	 *
	 * @param array $fields Existing native settings definitions.
	 * @return array
	 */
	public function fields( $fields ) {
		$fields['wpfrmgeo-autocomplete'] = [
			'wpfrmgeoac-heading'                   => [
				'id'      => 'wpfrmgeoac-heading',
				'content' => '<h4>' . esc_html__( 'Address Autocomplete', 'logical-address-autocomplete-for-wpforms' ) . '</h4><p>' . esc_html__( 'Use your own browser API key with Maps JavaScript API and Places API (New) enabled. Configure billing and restrict the key to your website. No server key is required.', 'logical-address-autocomplete-for-wpforms' ) . '</p>',
				'type'    => 'content',
			],
			'wpfrmgeo-google-maps-browser-api-key' => [
				'id'   => 'wpfrmgeo-google-maps-browser-api-key',
				'name' => __( 'Browser API Key', 'logical-address-autocomplete-for-wpforms' ),
				'type' => 'text',
			],
			'wpfrmgeo-default-region'              => [
				'id'      => 'wpfrmgeo-default-region',
				'name'    => __( 'Region Code', 'logical-address-autocomplete-for-wpforms' ),
				'type'    => 'text',
				'default' => 'US',
				'desc'    => __( 'A two-letter region code influences results; field country restrictions limit suggestions.', 'logical-address-autocomplete-for-wpforms' ),
			],
			'wpfrmgeo-default-language'            => [
				'id'      => 'wpfrmgeo-default-language',
				'name'    => __( 'Language', 'logical-address-autocomplete-for-wpforms' ),
				'type'    => 'text',
				'default' => 'en',
			],
		];
		return $fields;
	}

	/**
	 * Whitelist public browser configuration; never expose other host settings.
	 *
	 * @return array
	 */
	public static function get_config() {
		$all  = get_option( 'wpforms_settings', [] );
		$all  = is_array( $all ) ? $all : [];
		$read = static function ( $key, $default ) use ( $all ) {
			return isset( $all[ $key ] ) && is_scalar( $all[ $key ] ) ? sanitize_text_field( (string) $all[ $key ] ) : $default;
		};
		return [
			'googleMapsBrowserApiKey' => $read( 'wpfrmgeo-google-maps-browser-api-key', '' ),
			'regionCode'              => $read( 'wpfrmgeo-default-region', 'US' ),
			'languageCode'            => $read( 'wpfrmgeo-default-language', 'en' ),
			'disableGoogleApi'        => ! empty( $all['wpfrmgeo-disable-google-maps-api'] ),
		];
	}

}
