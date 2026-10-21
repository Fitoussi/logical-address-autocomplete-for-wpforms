<?php
/**
 * Plugin Name: Logical Address Autocomplete for WPForms
 * Description: Google Places suggestions for a dedicated Address field and native WPForms Address fields. Includes country, language, result-type and location-bias controls. Requires your own Google API key.
 * Version: 1.0.0
 * Author: Eyal Fitoussi
 * Author URI: https://www.wpgeo.com
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Text Domain: logical-address-autocomplete-for-wpforms
 * Domain Path: /languages/
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WPFormsGeolocationAutocomplete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPFRMGEOAC_VERSION', '1.0.0' );
define( 'WPFRMGEOAC_PLUGIN_FILE', __FILE__ );
define( 'WPFRMGEOAC_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPFRMGEOAC_PLUGIN_NAME', 'Address Autocomplete for WPForms' );
define( 'WPFRMGEOAC_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPFRMGEOAC_URL', plugin_dir_url( __FILE__ ) );
define( 'WPFRMGEOAC_PACKAGE_TYPE', 'free' );
define( 'WPFRMGEOAC_PACKAGE_LABEL', 'Address Autocomplete' );
define( 'WPFRMGEOAC_SITE_URL', 'https://wpgeoforms.com' );
define( 'WPFRMGEOAC_MIN_HOST_VERSION', '1.9.4.1' );

spl_autoload_register(
	static function ( $class_name ) {
		$prefix = 'WPFormsGeolocationAutocomplete\\';
		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}
		$file = __DIR__ . '/src/' . str_replace( '\\', '/', substr( $class_name, strlen( $prefix ) ) ) . '.php';
		if ( is_file( $file ) ) {
			require_once $file;
		}
	}
);

register_activation_hook( __FILE__, [ WPFormsGeolocationAutocomplete\Admin\SetupNotice::class, 'activate' ] );
add_action( 'plugins_loaded', [ WPFormsGeolocationAutocomplete\Loader::class, 'load' ], 20 );
