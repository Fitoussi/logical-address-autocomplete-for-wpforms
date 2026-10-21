<?php
/**
 * Local product overview for the standalone Address Autocomplete plugin.
 * This presentation-only page never reads licenses, creates forms or writes
 * settings. Actual configuration remains on the native WPForms screen.
 *
 * @package WPFormsGeolocationAutocomplete\Admin\Dashboard
 *
 * @since 1.0.0
 */

namespace WPFormsGeolocationAutocomplete\Admin\Dashboard;

use WPFormsGeolocationAutocomplete\Admin\SetupNotice;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Own local product-page metadata, routing and page-scoped presentation.
 *
 * @since 1.0.0
 */
final class Loader {

	/**
	 * Stable product-page identity independent of the installed folder name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private const PAGE_SLUG = 'wpfrmgeoac-dashboard';

	/**
	 * Native WordPress page hook returned during menu registration.
	 *
	 * @since 1.0.0
	 *
	 * @var string|false
	 */
	private static $page_hook = '';

	/**
	 * Register the local product page before WPForms initializes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register() {
		SetupNotice::register();
		add_action( 'admin_menu', [ self::class, 'register_page' ], 30 );
		add_action( 'admin_enqueue_scripts', [ self::class, 'enqueue' ] );
		add_filter( 'plugin_action_links_' . WPFRMGEOAC_BASENAME, [ self::class, 'add_action_link' ], 120 );
	}

	/**
	 * Place the overview with its WPForms host, without an account SDK.
	 * The Settings fallback keeps setup/help available while the host is inactive.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register_page() {
		$title = __( 'Address Autocomplete', 'logical-address-autocomplete-for-wpforms' );
		if ( function_exists( 'wpforms' ) ) {
			self::$page_hook = add_submenu_page( 'wpforms-overview', WPFRMGEOAC_PLUGIN_NAME, $title, 'manage_options', self::PAGE_SLUG, [ self::class, 'render' ] );
		} else {
			self::$page_hook = add_options_page( WPFRMGEOAC_PLUGIN_NAME, $title, 'manage_options', self::PAGE_SLUG, [ self::class, 'render' ] );
		}
	}

	/**
	 * Add the standalone plugin's overview, settings and documentation links.
	 *
	 * @since 1.0.0
	 *
	 * @param array $links Existing WordPress plugin action links.
	 * @return array
	 */
	public static function add_action_link( $links ) {
		return [
			'overview' => '<a href="' . esc_url( self::get_url() ) . '">' . esc_html__( 'Overview', 'logical-address-autocomplete-for-wpforms' ) . '</a>',
			'settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=wpforms-settings&view=wpfrmgeo-autocomplete' ) ) . '">' . esc_html__( 'Settings', 'logical-address-autocomplete-for-wpforms' ) . '</a>',
			'docs'     => '<a href="' . esc_url( WPFRMGEOAC_SITE_URL . '/docs/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Docs', 'logical-address-autocomplete-for-wpforms' ) . '</a>',
		] + $links;
	}

	/**
	 * Build a local navigation link using a whitelisted, non-mutating section.
	 *
	 * @since 1.0.0
	 *
	 * @param string $section Requested section.
	 * @return string
	 */
	public static function get_url( $section = 'overview' ) {
		$section = \in_array( $section, [ 'overview', 'comparison', 'help', 'products' ], true ) ? $section : 'overview';
		return add_query_arg( 'section', $section, admin_url( 'admin.php?page=' . self::PAGE_SLUG ) );
	}

	/**
	 * Load scoped styles on the product page or its native settings subview.
	 * Native settings need only our card styles, not framework dashboard assets.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook_suffix WordPress screen hook.
	 * @return void
	 */
	public static function enqueue( $hook_suffix ) {
		$is_dashboard = ! empty( self::$page_hook ) && self::$page_hook === $hook_suffix;
		// Navigation context only; no submitted settings or mutation are read here.
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$page = isset( $_GET['page'] ) && \is_string( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		$is_settings = 'wpforms-settings' === $page;

		if ( ! $is_dashboard && ! $is_settings ) {
			return;
		}

		wp_enqueue_style(
			'wpfrmgeo-product-dashboard',
			WPFRMGEOAC_URL . 'src/Admin/Dashboard/assets/css/dashboard.min.css',
			[],
			WPFRMGEOAC_VERSION
		);
	}

	/**
	 * Render overview discovery links with no inputs, licensing or remote content.
	 * Link to the internal feature overview first and external pricing second.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function render_settings_link() {
		?>
		<div class="wpfrmgeo-product-settings__content wpfrmgeoac-settings-notice">
			<h3><?php esc_html_e( 'Need more than address autocomplete?', 'logical-address-autocomplete-for-wpforms' ); ?></h3>
			<p>
				<?php esc_html_e( 'Explore maps, current-location detection, connected fields and directions with Geolocation for WPForms.', 'logical-address-autocomplete-for-wpforms' ); ?>
			</p>
			<div class="wpfrmgeo-product-settings__actions">
				<a class="button button-primary" href="<?php echo esc_url( self::get_url() ); ?>">
					<?php esc_html_e( 'Explore features', 'logical-address-autocomplete-for-wpforms' ); ?>
				</a>
				<a class="button button-secondary" href="<?php echo esc_url( WPFRMGEOAC_SITE_URL . '/pricing/' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View packages', 'logical-address-autocomplete-for-wpforms' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the selected server-side tab; malformed requests fall back to Overview.
	 * WordPress already gates the submenu. The second check also protects direct
	 * callback invocation. The template receives public metadata/URLs only.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// This GET value selects presentation only: there is no save/delete action.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$requested = isset( $_GET['section'] ) && \is_string( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : '';
		$tabs      = [
			'overview'   => __( 'Overview', 'logical-address-autocomplete-for-wpforms' ),
			'comparison' => __( 'Compare Packages', 'logical-address-autocomplete-for-wpforms' ),
			'help'       => __( 'Help', 'logical-address-autocomplete-for-wpforms' ),
			'products'   => __( 'More Plugins', 'logical-address-autocomplete-for-wpforms' ),
		];

		$version           = WPFRMGEOAC_VERSION;
		$section           = isset( $tabs[ $requested ] ) ? $requested : 'overview';
		$settings_url      = admin_url( 'admin.php?page=wpforms-settings&view=wpfrmgeo-autocomplete' );
		$pricing_url       = WPFRMGEOAC_SITE_URL . '/pricing/';
		$docs_url          = WPFRMGEOAC_SITE_URL . '/docs/';
		$demo_url          = 'https://wpgeoforms.com/';
		$product_name      = __( 'Address Autocomplete', 'logical-address-autocomplete-for-wpforms' );
		$package_name      = WPFRMGEOAC_PACKAGE_LABEL;
		$features          = Features::get_features();
		$overview_features = Features::order_for_overview( $features );
		$included          = array_filter(
			$overview_features,
			static function ( $feature ) {
				return $feature['included'];
			}
		);
		$available         = array_filter(
			$overview_features,
			static function ( $feature ) {
				return ! $feature['included'];
			}
		);
		$packages          = Features::get_packages();

		$hero_title     = __( 'Less typing. Better addresses.', 'logical-address-autocomplete-for-wpforms' );
		$help_step      = __( 'Add the Address field from the Geolocation group, or enable autocomplete on a native Address field if your WPForms edition provides it.', 'logical-address-autocomplete-for-wpforms' );
		$feature_groups = [
			'included'  => $included,
			'available' => $available,
		];

		require __DIR__ . '/views/dashboard.php';
	}

	/**
	 * Return bundled product descriptions without remote content or installers.
	 *
	 * @since 1.0.0
	 *
	 * @return array[] Public product names, descriptions and website URLs.
	 */
	public static function get_products() {
		return [
			[
				'name'        => 'Formidable Geolocation',
				'url'         => 'https://formidablegeolocation.com/',
				'description' => __( 'Maps, geocoding and location workflows for Formidable Forms.', 'logical-address-autocomplete-for-wpforms' ),
			],
			[
				'name'        => 'GEO my WP',
				'url'         => 'https://geomywp.com/',
				'description' => __( 'Build location-based searches and directories for your WordPress site.', 'logical-address-autocomplete-for-wpforms' ),
			],
			[
				'name'        => 'Gravity Geolocation',
				'url'         => 'https://gravitygeolocation.com/',
				'description' => __( 'Maps, geocoding, dynamic fields, directions, nearby locations and more for Gravity Forms.', 'logical-address-autocomplete-for-wpforms' ),
			],
			[
				'name'        => 'Gravity Search',
				'url'         => 'https://gravitygeolocation.com/solutions/gravity-search/',
				'description' => __( 'Turn Gravity Forms entries into searchable directories, listings and customer portals—with field filters, proximity search, maps and flexible result layouts.', 'logical-address-autocomplete-for-wpforms' ),
			],
			[
				'name'        => 'Geolocation for Ninja Forms',
				'url'         => 'https://ninjageolocation.com/',
				'description' => __( 'Bring address, map and location workflows to Ninja Forms.', 'logical-address-autocomplete-for-wpforms' ),
			],
			[
				'name'        => 'WPForms Geolocation',
				'url'         => 'https://wpgeoforms.com/',
				'description' => __( 'Add address autocomplete, maps, coordinates and directions to WPForms.', 'logical-address-autocomplete-for-wpforms' ),
			],
			[
				'name'        => 'WP Job Manager Geolocation',
				'url'         => 'https://jobmanagergeolocation.com/',
				'description' => __( 'Add proximity searches and interactive maps to your WP Job Manager job board.', 'logical-address-autocomplete-for-wpforms' ),
			],
		];
	}
}
