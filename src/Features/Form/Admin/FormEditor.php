<?php
/**
 * Native builder controls and non-inserting premium discovery buttons.
 *
 * @package WPFormsGeolocationAutocomplete\Features\Form\Admin
 */

namespace WPFormsGeolocationAutocomplete\Features\Form\Admin;

use WPFormsGeolocationAutocomplete\Features\Form\Fields\Address\Settings;
use WPFormsGeolocationAutocomplete\Helpers\ReferenceData;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keep supported field preferences separate from unavailable paid fields.
 */
final class FormEditor {

	/**
	 * Register scoped native builder integrations.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'wpforms_builder_fields_buttons', [ $this, 'buttons' ], 10 );
		add_filter( 'wpforms_builder_fields_buttons', [ $this, 'free_buttons' ], 600 );
		add_filter( 'wpforms_builder_field_button_attributes', [ $this, 'button_attributes' ], 20, 2 );
		add_action( 'wpforms_field_options_after_advanced-options', [ $this, 'options' ], 5 );
		add_action( 'wpforms_builder_enqueues', [ $this, 'enqueue' ] );
	}

	/**
	 * Four real premium capabilities; these identifiers have no field classes.
	 *
	 * @return array
	 */
	public static function promotions() {
		return [
			'map'         => __( 'Map', 'logical-address-autocomplete-for-wpforms' ),
			'directions'  => __( 'Directions', 'logical-address-autocomplete-for-wpforms' ),
			'coordinates' => __( 'Coordinates', 'logical-address-autocomplete-for-wpforms' ),
			'locator'     => __( 'Locator Button', 'logical-address-autocomplete-for-wpforms' ),
		];
	}

	/**
	 * Add the group before WPForms populates the actual field buttons.
	 *
	 * @param array $groups Existing native groups.
	 * @return array
	 */
	public function buttons( $groups ) {
		$group = [
			'group_name' => __( 'Geolocation', 'logical-address-autocomplete-for-wpforms' ),
			'fields'     => [],
		];
		foreach ( self::promotions() as $id => $label ) {
			$group['fields'][] = [
				'type'     => 'wpfrmgeoac_promo_' . $id,
				'name'     => $label,
				'icon'     => 'fa-lock',
				'order'    => 10,
				'class'    => 'wpfrmgeoac-promo',
				'keywords' => '',
			];
		}
		return array_slice( $groups, 0, 1, true ) + [ 'wpfrmgeo_geolocation' => $group ] + array_slice( $groups, 1, null, true );
	}

	/**
	 * Keep this plugin's free field and previews independent of host paid metadata.
	 * WPForms labels every nonstandard group as licensed on an unlicensed paid
	 * installation. Only our own registered identifiers are corrected here;
	 * native paid fields, other add-ons and the host license state are untouched.
	 *
	 * @param array $groups Native groups after host education metadata is applied.
	 * @return array
	 */
	public function free_buttons( $groups ) {
		$types = [ 'wpfrmgeo_address' ];
		foreach ( array_keys( self::promotions() ) as $id ) {
			$types[] = 'wpfrmgeoac_promo_' . $id;
		}
		foreach ( $groups['wpfrmgeo_geolocation']['fields'] ?? [] as $index => $field ) {
			if ( in_array( $field['type'] ?? '', $types, true ) ) {
				unset( $groups['wpfrmgeo_geolocation']['fields'][ $index ]['action'] );
			}
		}
		return $groups;
	}

	/**
	 * Remove native insertion behavior from promotional buttons, including dragging.
	 *
	 * @param array $attributes Native button attributes.
	 * @param array $field Button metadata.
	 * @return array
	 */
	public function button_attributes( $attributes, $field ) {
		$type = $field['type'] ?? '';
		if ( 0 !== strpos( $type, 'wpfrmgeoac_promo_' ) ) {
			return $attributes;
		}
		$attributes['class'] = [ 'wpforms-add-fields-button', 'not-draggable', 'wpfrmgeoac-promo' ];
		$attributes['data']  = [ 'wpfrmgeoac-feature' => substr( $type, strlen( 'wpfrmgeoac_promo_' ) ) ];
		$attributes['atts']  = [
			'type'          => 'button',
			'aria-label'    => sprintf(
				/* translators: %s: Name of a premium feature shown only as a preview. */
				__( '%s — premium feature preview', 'logical-address-autocomplete-for-wpforms' ),
				$field['name']
			),
			'aria-haspopup' => 'dialog',
			'title'         => __( 'Available in Geolocation for WPForms — learn more', 'logical-address-autocomplete-for-wpforms' ),
		];
		return $attributes;
	}

	/**
	 * Render a native option-group tab directly after Advanced for supported fields.
	 *
	 * @param array $field Saved field definition.
	 * @return void
	 */
	public function options( $field ) {
		if ( ! in_array( $field['type'] ?? '', [ 'wpfrmgeo_address', 'address' ], true ) || ( 'address' === $field['type'] && ! wpforms()->is_pro() ) ) {
			return;
		}
		$values = Settings::export( $field );
		if ( 'address' === $field['type'] && ! isset( $field['geo_address_autocomplete'] ) ) {
			$values['geo_address_autocomplete'] = '';
		}
		echo '<div class="wpforms-field-option-group wpforms-field-option-group-wpfrmgeoac" id="wpforms-field-option-wpfrmgeoac-' . absint( $field['id'] ) . '">';
		echo '<a href="#" class="wpforms-field-option-group-toggle">' . esc_html__( 'Address Autocomplete', 'logical-address-autocomplete-for-wpforms' ) . '</a>';
		echo '<div class="wpforms-field-option-group-inner"><div class="wpfrmgeoac-field-options">';
		$definitions = [
			'geo_address_autocomplete'                 => [ __( 'Enable autocomplete', 'logical-address-autocomplete-for-wpforms' ), 'toggle' ],
			'geo_force_autocomplete_selection'         => [ __( 'Require a suggestion selection', 'logical-address-autocomplete-for-wpforms' ), 'toggle' ],
			'geo_force_autocomplete_selection_message' => [ __( 'Selection alert message', 'logical-address-autocomplete-for-wpforms' ), 'text' ],
			'geo_address_autocomplete_types'           => [ __( 'Result types (up to 5)', 'logical-address-autocomplete-for-wpforms' ), 'multiple', ReferenceData::get_place_types_as_options() ],
			'geo_address_autocomplete_countries'       => [ __( 'Restrict to countries (up to 5)', 'logical-address-autocomplete-for-wpforms' ), 'multiple', ReferenceData::get_countries_as_options() ],
			'geo_address_autocomplete_language'        => [
				__( 'Language', 'logical-address-autocomplete-for-wpforms' ),
				'select',
				array_merge(
					[
						[
							'value' => '',
							'label' => __( 'Use global language', 'logical-address-autocomplete-for-wpforms' ),
						],
					],
					ReferenceData::get_languages_as_options()
				),
			],
			'geo_autocomplete_restriction_usage'       => [
				__( 'Location bias', 'logical-address-autocomplete-for-wpforms' ),
				'select',
				[
					''            => __( 'Disabled', 'logical-address-autocomplete-for-wpforms' ),
					'proximity'   => __( 'Proximity', 'logical-address-autocomplete-for-wpforms' ),
					'area_bounds' => __( 'Area bounds', 'logical-address-autocomplete-for-wpforms' ),
				],
			],
			'geo_autocomplete_proximity_lat'           => [ __( 'Latitude', 'logical-address-autocomplete-for-wpforms' ), 'text' ],
			'geo_autocomplete_proximity_lng'           => [ __( 'Longitude', 'logical-address-autocomplete-for-wpforms' ), 'text' ],
			'geo_autocomplete_proximity_radius'        => [ __( 'Radius (meters, up to 50,000)', 'logical-address-autocomplete-for-wpforms' ), 'text' ],
			'geo_autocomplete_bounds_sw_point'         => [ __( 'Southwest point (latitude, longitude)', 'logical-address-autocomplete-for-wpforms' ), 'text' ],
			'geo_autocomplete_bounds_ne_point'         => [ __( 'Northeast point (latitude, longitude)', 'logical-address-autocomplete-for-wpforms' ), 'text' ],
			'geo_address_autocomplete_strict_bounds'   => [ __( 'Restrict results to the area bounds', 'logical-address-autocomplete-for-wpforms' ), 'toggle' ],
		];
		foreach ( $definitions as $key => $definition ) {
			$this->control( $field, $key, $definition, $values[ $key ] );
			if ( 'geo_address_autocomplete' === $key ) {
				printf(
					'<div class="wpforms-panel-field-toggle-body wpfrmgeoac-enabled-options" data-toggle="%s" data-toggle-value="1"%s>',
					esc_attr( 'fields[' . absint( $field['id'] ) . '][geo_address_autocomplete]' ),
					Settings::enabled( $values[ $key ] ) ? '' : ' style="display:none"'
				);
			}
		}
		echo '</div></div></div></div>';
	}

	/**
	 * Output escaped native-compatible names; multi-selects persist comma lists.
	 *
	 * @param array  $field Field identity.
	 * @param string $key Saved key.
	 * @param array  $definition Label, type and optional choices.
	 * @param string $value Saved scalar value.
	 * @return void
	 */
	private function control( $field, $key, $definition, $value ) {
		$id         = 'wpforms-field-option-' . absint( $field['id'] ) . '-' . $key;
		$name       = 'fields[' . absint( $field['id'] ) . '][' . $key . ']';
		$controller = '';
		$expected   = '';
		if ( 'geo_force_autocomplete_selection_message' === $key ) {
			$controller = 'geo_force_autocomplete_selection';
			$expected   = '1';
		} elseif ( 0 === strpos( $key, 'geo_autocomplete_proximity_' ) ) {
			$controller = 'geo_autocomplete_restriction_usage';
			$expected   = 'proximity';
		} elseif ( 0 === strpos( $key, 'geo_autocomplete_bounds_' ) || 'geo_address_autocomplete_strict_bounds' === $key ) {
			$controller = 'geo_autocomplete_restriction_usage';
			$expected   = 'area_bounds';
		}
		$controllers  = [ 'geo_address_autocomplete', 'geo_force_autocomplete_selection', 'geo_autocomplete_restriction_usage' ];
		$toggle_class = in_array( $key, $controllers, true ) ? 'wpforms-panel-field-toggle' : '';
		echo '<div class="wpforms-field-option-row' . ( $controller ? ' wpforms-panel-field-toggle-body' : '' ) . '" data-wpfrmgeoac-option="' . esc_attr( $key ) . '"';
		if ( $controller ) {
			$defaults = Settings::defaults();
			$current  = $field[ $controller ] ?? $defaults[ $controller ];
			$visible  = '1' === $expected ? Settings::enabled( $current ) : $expected === $current;
			echo ' data-toggle="' . esc_attr( 'fields[' . absint( $field['id'] ) . '][' . $controller . ']' ) . '" data-toggle-value="' . esc_attr( $expected ) . '"' . ( $visible ? '' : ' style="display:none"' );
		}
		echo '>';
		$help    = [
			'geo_force_autocomplete_selection'       => __( 'Require visitors to select an address from the suggestions.', 'logical-address-autocomplete-for-wpforms' ),
			'geo_address_autocomplete_types'         => __( 'Choose up to 5 place types, or leave empty for all types.', 'logical-address-autocomplete-for-wpforms' ),
			'geo_autocomplete_restriction_usage'     => __( 'Prioritize suggestions near a specified location.', 'logical-address-autocomplete-for-wpforms' ),
			'geo_address_autocomplete_language'      => __( 'Choose the suggestion language for this field, or use the global language setting.', 'logical-address-autocomplete-for-wpforms' ),
			'geo_address_autocomplete_strict_bounds' => __( 'Limit suggestions to the configured area instead of only prioritizing nearby results.', 'logical-address-autocomplete-for-wpforms' ),
		];
		$tooltip = $help[ $key ] ?? '';
		if ( 'geo_address_autocomplete' === $key && Settings::official_owns( $field ) ) {
			$tooltip = __( 'WPForms’ official Geolocation add-on currently handles this field. Turn off its autocomplete before using these settings. This plugin will not initialize a second autocomplete on the same field.', 'logical-address-autocomplete-for-wpforms' );
		}
		$label = '<label for="' . esc_attr( $id ) . '">' . esc_html( $definition[0] );
		if ( $tooltip ) {
			// Use the same help icon and title contract as WPForms field labels.
			$label .= '<i class="fa fa-question-circle-o wpforms-help-tooltip" title="' . esc_attr( $tooltip ) . '"></i>';
		}
		$label .= '</label>';
		if ( 'toggle' === $definition[1] ) {
			echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="">';
			$toggle_args = [ 'input-class' => $toggle_class ];
			if ( $tooltip ) {
				$toggle_args['tooltip'] = $tooltip;
			}
			// WPForms renders its own switch markup; keep the existing storage contract.
			echo wpforms_panel_field_toggle_control( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Native WPForms renderer with escaped labels and identifiers.
				$toggle_args,
				$id,
				$name,
				esc_html( $definition[0] ),
				Settings::enabled( $value ) ? '1' : '',
				''
			);
		} elseif ( in_array( $definition[1], [ 'select', 'multiple' ], true ) ) {
			echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped immediately above.
			$multiple = 'multiple' === $definition[1];
			if ( $multiple ) {
				echo '<input type="hidden" class="wpfrmgeoac-list-value" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
			}
			echo '<select id="' . esc_attr( $id ) . '" ' . ( $multiple ? 'multiple class="wpfrmgeoac-multiple"' : 'class="' . esc_attr( $toggle_class ) . '" name="' . esc_attr( $name ) . '"' ) . '>';
			if ( $multiple ) {
				foreach ( explode( ',', $value ) as $saved ) {
					if ( '' !== $saved && ! in_array( $saved, array_column( $definition[2], 'value' ), true ) ) {
						$definition[2][] = [
							'value' => $saved,
							'label' => $saved,
						];
					}
				}
			}
			foreach ( $definition[2] as $choice_key => $choice ) {
				$option_value = is_array( $choice ) ? ( $choice['value'] ?? '' ) : $choice_key;
				$option_label = is_array( $choice ) ? ( $choice['label'] ?? '' ) : $choice;
				$selected     = $multiple ? in_array( (string) $option_value, explode( ',', $value ), true ) : (string) $option_value === $value;
				echo '<option value="' . esc_attr( $option_value ) . '" ' . selected( $selected, true, false ) . '>' . esc_html( $option_label ) . '</option>';
			}
			echo '</select>';
			if ( 'geo_address_autocomplete_types' === $key ) {
				echo '<span class="description autocomplete-type-collection-note" hidden>' . esc_html__( 'Cities and Regions must each be used alone; selecting one replaces your other choices.', 'logical-address-autocomplete-for-wpforms' ) . '</span>';
			}
		} else {
			echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped immediately above.
			echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
		}
		echo '</div>';
	}

	/**
	 * Load only in the native form builder.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'wpfrmgeoac-editor', WPFRMGEOAC_URL . 'build/js/admin/wpfrmgeo-form-editor.min.js', [ 'jquery' ], WPFRMGEOAC_VERSION, true );
		wp_enqueue_style( 'wpfrmgeoac-editor', WPFRMGEOAC_URL . 'build/css/admin/wpfrmgeo-admin.min.css', [], WPFRMGEOAC_VERSION );
		wp_enqueue_style( 'wpfrmgeoac-choices', WPFRMGEOAC_URL . 'assets/css/choices.css', [], WPFRMGEOAC_VERSION );
		wp_localize_script(
			'wpfrmgeoac-editor',
			'wpfrmgeoacEditor',
			[
				'labels'  => self::promotions(),
				'title'   => __( 'Available in Geolocation for WPForms', 'logical-address-autocomplete-for-wpforms' ),
				'message' => __( 'This is a premium feature preview. It cannot add a field or change your form.', 'logical-address-autocomplete-for-wpforms' ),
				'learn'   => __( 'Learn more', 'logical-address-autocomplete-for-wpforms' ),
				'close'   => __( 'Close', 'logical-address-autocomplete-for-wpforms' ),
				'url'     => WPFRMGEOAC_SITE_URL . '/',
			]
		);
	}

}
