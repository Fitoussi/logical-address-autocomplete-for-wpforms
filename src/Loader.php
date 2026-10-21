<?php
/**
 * Deferred dependency and premium ownership checks.
 *
 * @package WPFormsGeolocationAutocomplete
 */

namespace WPFormsGeolocationAutocomplete;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register only after all active plugin entry points have loaded.
 */
final class Loader {

	/**
	 * Check the premium bootstrap, whose version constant is defined later on init.
	 *
	 * @return bool Whether premium owns the saved field types.
	 */
	public static function premium_active() {
		return class_exists( 'Geolocation_For_WPForms', false ) || defined( 'WPWPFRMGEO_VERSION' );
	}

	/**
	 * Connect the standalone plugin without initializing paid services.
	 *
	 * @return void
	 */
	public static function load() {
		if ( self::premium_active() ) {
			add_action( 'admin_notices', [ self::class, 'conflict_notice' ] );
			add_action( 'network_admin_notices', [ self::class, 'conflict_notice' ] );
			return;
		}
		Admin\Dashboard\Loader::register();
		if ( ! defined( 'WPFORMS_VERSION' ) || version_compare( WPFORMS_VERSION, WPFRMGEOAC_MIN_HOST_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ self::class, 'dependency_notice' ] );
			return;
		}
		new Admin\Settings();
		new Features\Form\Admin\FormEditor();
		new Features\Form\Runtime\FormGeoProcessor();
		// WPForms loads its Text parent class on init at priority 10.
		add_action( 'init', [ self::class, 'register_field' ], 20 );
	}

	/**
	 * Register the real field after WPForms' base fields are available.
	 *
	 * @return void
	 */
	public static function register_field() {
		if ( ! self::premium_active() && class_exists( 'WPForms_Field_Text' ) ) {
			new Features\Form\Fields\Address\Field();
		}
	}

	/**
	 * Explain ownership without auto-deactivation or deleting saved data.
	 *
	 * @return void
	 */
	public static function conflict_notice() {
		if ( current_user_can( 'activate_plugins' ) ) {
			echo '<div class="notice notice-warning"><p>' . esc_html__( 'Address Autocomplete for WPForms is paused because Geolocation for WPForms is active. Deactivate the free plugin. Saved fields and settings are retained. Autocomplete options depend on your premium version; check that version and test your forms when switching.', 'logical-address-autocomplete-for-wpforms' ) . '</p></div>';
		}
	}

	/**
	 * Explain the supported host editions and minimum version.
	 *
	 * @return void
	 */
	public static function dependency_notice() {
		if ( current_user_can( 'activate_plugins' ) ) {
			echo '<div class="notice notice-warning"><p>' . esc_html__( 'Address Autocomplete requires WPForms Lite or paid WPForms 1.9.4.1 or newer.', 'logical-address-autocomplete-for-wpforms' ) . '</p></div>';
		}
	}

}
