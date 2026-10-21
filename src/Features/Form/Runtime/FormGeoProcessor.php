<?php
/**
 * Immutable frontend configuration for rendered WPForms instances.
 *
 * @package WPFormsGeolocationAutocomplete\Features\Form\Runtime
 */

namespace WPFormsGeolocationAutocomplete\Features\Form\Runtime;

use WPFormsGeolocationAutocomplete\Admin\Settings;
use WPFormsGeolocationAutocomplete\Features\Form\Fields\Address\Settings as FieldSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue only where an enabled, supported Address field is rendered.
 */
final class FormGeoProcessor {

	/**
	 * Register at the native render boundary, including previews and AJAX renders.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wpforms_frontend_output_before', [ $this, 'collect' ], 20 );
	}

	/**
	 * Collect browser-safe field preferences without changing the host form.
	 *
	 * @param array $form_data Native form definition.
	 * @return void
	 */
	public function collect( $form_data ) {
		if ( empty( $form_data['id'] ) ) {
			return;
		}
		$fields = [];
		foreach ( $form_data['fields'] ?? [] as $field ) {
			if ( ! isset( $field['id'] ) ) {
				continue;
			}
			$type = $field['type'] ?? '';
			if ( ! in_array( $type, [ 'wpfrmgeo_address', 'address' ], true ) || ( 'address' === $type && ( ! wpforms()->is_pro() || FieldSettings::official_owns( $field ) ) ) ) {
				continue;
			}
			$enabled = $field['geo_address_autocomplete'] ?? ( 'wpfrmgeo_address' === $type ? '1' : '' );
			if ( ! FieldSettings::enabled( $enabled ) ) {
				continue;
			}
			$fields[] = array_merge(
				FieldSettings::export( $field ),
				[
					'id'   => (string) $field['id'],
					'type' => $type,
				]
			);
		}
		if ( ! $fields ) {
			return;
		}
		$data = [
			(string) $form_data['id'] => [
				'formId' => (string) $form_data['id'],
				'fields' => $fields,
				'config' => Settings::get_config(),
			],
		];
		wp_enqueue_script( 'wpfrmgeoac-autocomplete', WPFRMGEOAC_URL . 'build/js/frontend/address-autocomplete.min.js', [ 'jquery' ], WPFRMGEOAC_VERSION, true );
		wp_enqueue_style( 'wpfrmgeoac-autocomplete', WPFRMGEOAC_URL . 'assets/css/address-autocomplete.css', [], WPFRMGEOAC_VERSION );
		wp_add_inline_script( 'wpfrmgeoac-autocomplete', 'window.wpfrmgeoAutocompleteForms = Object.assign(window.wpfrmgeoAutocompleteForms || {}, ' . wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . ');', 'before' );
	}

}
