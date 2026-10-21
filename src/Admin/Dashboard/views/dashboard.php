<?php
/**
 * Standalone product overview. Public variables come from Admin\Dashboard\Loader::render().
 * Screenshot markers reserve our own local assets; no remote embeds or tracking.
 * Availability markers describe packaged implementations, not completed setup.
 *
 * @package WPFormsGeolocationAutocomplete\Admin
 * @since 1.0.0
 */

use WPFormsGeolocationAutocomplete\Admin\Dashboard\Loader as Dashboard;

// Included inside Dashboard::render(); these presentation variables are method-local.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap wpfrmgeo-product-dashboard<?php echo 'overview' === $section ? ' wpfrmgeo-product-dashboard--overview' : ''; ?>">
	<header class="wpfrmgeo-product-dashboard__header">
		<div>
			<p class="wpfrmgeo-product-dashboard__eyebrow"><?php esc_html_e( 'For WPForms', 'logical-address-autocomplete-for-wpforms' ); ?></p>
			<h1><?php echo esc_html( $product_name ); ?></h1>
		</div>
		<div class="wpfrmgeo-product-dashboard__actions">
			<span class="wpfrmgeo-product-dashboard__version"><?php echo esc_html( 'v' . $version ); ?></span>
			<a class="button button-primary" href="<?php echo esc_url( $settings_url ); ?>"><?php esc_html_e( 'Open Settings', 'logical-address-autocomplete-for-wpforms' ); ?></a>
			<a class="button" href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Explore Geolocation', 'logical-address-autocomplete-for-wpforms' ); ?></a>
			<a class="button" href="<?php echo esc_url( Dashboard::get_url( 'products' ) ); ?>"><?php esc_html_e( 'Explore Plugins', 'logical-address-autocomplete-for-wpforms' ); ?></a>
		</div>
	</header>
	<nav class="wpfrmgeo-product-dashboard__nav" aria-label="<?php esc_attr_e( 'Product overview sections', 'logical-address-autocomplete-for-wpforms' ); ?>">
		<?php foreach ( $tabs as $key => $label ) : ?>
			<a href="<?php echo esc_url( Dashboard::get_url( $key ) ); ?>" aria-current="<?php echo $section === $key ? 'page' : 'false'; ?>"><?php echo esc_html( $label ); ?></a>
		<?php endforeach; ?>
	</nav>
	<hr class="wp-header-end">
	<div class="wpfrmgeo-product-dashboard__layout">
		<main class="wpfrmgeo-product-dashboard__main">
			<?php if ( 'overview' === $section ) : ?>
				<section class="wpfrmgeo-product-dashboard__card wpfrmgeo-product-dashboard__hero">
					<div>
						<p class="wpfrmgeo-product-dashboard__eyebrow"><?php esc_html_e( 'Your installed package', 'logical-address-autocomplete-for-wpforms' ); ?></p>
						<h2><?php echo esc_html( $hero_title ); ?></h2>
						<p><?php esc_html_e( 'Modern Google Places autocomplete, included free. Collect addresses with a dedicated single-line field or native Address autofill, tailor suggestions to your audience, and require a selection when it matters.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
						<div class="wpfrmgeo-product-dashboard__actions">
							<a class="button button-primary" href="#included-features"><?php esc_html_e( 'Explore my features', 'logical-address-autocomplete-for-wpforms' ); ?></a>
							<?php
							if ( $available ) :
								?>
								<a class="button" href="#available-features"><?php esc_html_e( 'See what else is available', 'logical-address-autocomplete-for-wpforms' ); ?></a><?php endif; ?>
						</div>
						<p class="wpfrmgeo-product-dashboard__note"><?php esc_html_e( 'Location tools require your own Google API configuration. Google service usage and billing are separate.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
					</div>
					<div class="wpfrmgeo-product-dashboard__package-summary">
						<span class="wpfrmgeo-product-dashboard__badge"><?php esc_html_e( 'Installed package', 'logical-address-autocomplete-for-wpforms' ); ?></span>
						<h3><?php echo esc_html( $package_name ); ?></h3>
						<p><?php esc_html_e( 'Badges identify included tools. Follow their setup guides to put them to work.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
						<a href="<?php echo esc_url( $settings_url ); ?>"><?php esc_html_e( 'Configuration & settings', 'logical-address-autocomplete-for-wpforms' ); ?></a>
					</div>
				</section>
				<?php
				foreach ( $feature_groups as $group => $cards ) :
					if ( ! $cards ) {
						continue;
					}
					?>
					<section id="<?php echo esc_attr( $group . '-features' ); ?>" class="wpfrmgeo-product-dashboard__feature-group" aria-labelledby="<?php echo esc_attr( $group . '-heading' ); ?>">
						<div class="wpfrmgeo-product-dashboard__group-heading">
							<h2 id="<?php echo esc_attr( $group . '-heading' ); ?>"><?php echo esc_html( 'included' === $group ? __( 'Included in your package', 'logical-address-autocomplete-for-wpforms' ) : __( 'Take your forms further', 'logical-address-autocomplete-for-wpforms' ) ); ?></h2>
							<p><?php echo esc_html( 'included' === $group ? __( 'Your available building blocks. Choose a tool and open its guide.', 'logical-address-autocomplete-for-wpforms' ) : __( 'Other WPForms packages add these capabilities. Explore the demos or compare packages to find the right fit.', 'logical-address-autocomplete-for-wpforms' ) ); ?></p>
						</div>
						<div class="wpfrmgeo-product-dashboard__feature-grid">
							<?php foreach ( $cards as $item_id => $feature ) : ?>
								<article class="wpfrmgeo-product-dashboard__card wpfrmgeo-product-dashboard__feature <?php echo $feature['included'] ? 'is-included' : 'is-available'; ?>" data-feature="<?php echo esc_attr( $item_id ); ?>">
									<div class="wpfrmgeo-product-dashboard__feature-top">
										<span class="dashicons dashicons-<?php echo esc_attr( $feature['icon'] ); ?>" aria-hidden="true"></span>
										<span class="wpfrmgeo-product-dashboard__badge <?php echo $feature['included'] ? '' : 'wpfrmgeo-product-dashboard__badge--available'; ?>"><?php echo esc_html( $feature['included'] ? __( 'Included in your package', 'logical-address-autocomplete-for-wpforms' ) : __( 'Available in other packages', 'logical-address-autocomplete-for-wpforms' ) ); ?></span>
									</div>
									<h3><?php echo esc_html( $feature['title'] ); ?></h3>
									<p><?php echo esc_html( $feature['description'] ); ?></p>
									<p class="wpfrmgeo-product-dashboard__note"><?php echo esc_html( $feature['details'] ); ?></p>
									<div class="wpfrmgeo-product-dashboard__feature-footer">
										<?php if ( $feature['included'] ) : ?>
											<a href="<?php echo esc_url( $docs_url . $feature['doc'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Setup & documentation', 'logical-address-autocomplete-for-wpforms' ); ?><span class="screen-reader-text"> — <?php echo esc_html( $feature['title'] ); ?></span></a>
										<?php else : ?>
											<p><span class="dashicons dashicons-category" aria-hidden="true"></span><span>
											<?php
												/* translators: %s: Comma-separated package names that include this feature. */
												echo esc_html( sprintf( __( 'Included in: %s', 'logical-address-autocomplete-for-wpforms' ), implode( ', ', array_intersect_key( $packages, array_flip( $feature['packages'] ) ) ) ) );
											?>
											</span></p>
											<?php $feature_demos = \WPFormsGeolocationAutocomplete\Admin\Dashboard\Features::get_demo_links( $item_id ); ?>
											<?php if ( $feature_demos ) : ?>
												<div class="wpfrmgeo-product-dashboard__demo-links">
													<?php foreach ( $feature_demos as $feature_demo ) : ?>
														<a href="<?php echo esc_url( $feature_demo['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $feature_demo['label'] ); ?><span class="screen-reader-text"> — <?php echo esc_html( $feature['title'] ); ?></span></a>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endforeach; ?>
				<?php if ( $available ) : ?>
					<section class="wpfrmgeo-product-dashboard__upgrade">
						<h2><?php esc_html_e( 'Choose the tools your next project needs', 'logical-address-autocomplete-for-wpforms' ); ?></h2>
						<p><?php esc_html_e( 'Geolocation for WPForms adds connected maps, geocoding, coordinates, locator controls and directions. It is a separate alternative plugin, not an add-on to activate alongside free.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
						<a class="button button-primary" href="<?php echo esc_url( $pricing_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View packages & pricing', 'logical-address-autocomplete-for-wpforms' ); ?></a>
					</section>
				<?php endif; ?>
			<?php elseif ( 'comparison' === $section ) : ?>
				<section class="wpfrmgeo-product-dashboard__card">
					<h2><?php esc_html_e( 'Find the right package for your project', 'logical-address-autocomplete-for-wpforms' ); ?></h2>
					<p><?php esc_html_e( 'Your installed free plugin is highlighted. Premium is an alternative plugin, not an add-on to activate alongside free. Saved settings are retained. This comparison describes the latest premium implementation; older releases may differ. Test your forms before switching.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
					<p class="wpfrmgeo-product-dashboard__note"><?php esc_html_e( 'On narrow screens, scroll the comparison horizontally to see every package.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
					<div class="wpfrmgeo-product-dashboard__table-wrap" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Feature comparison — scroll to see all columns', 'logical-address-autocomplete-for-wpforms' ); ?>">
						<table>
							<caption class="screen-reader-text"><?php esc_html_e( 'WPForms package feature comparison', 'logical-address-autocomplete-for-wpforms' ); ?></caption>
							<thead>
								<tr>
									<th scope="col"><?php esc_html_e( 'Feature', 'logical-address-autocomplete-for-wpforms' ); ?></th>
									<?php foreach ( $packages as $package_id => $label ) : ?>
										<th scope="col" class="<?php echo WPFRMGEOAC_PACKAGE_TYPE === $package_id ? 'is-current' : ''; ?>">
											<?php echo esc_html( $label ); ?>
											<?php if ( WPFRMGEOAC_PACKAGE_TYPE === $package_id ) : ?>
												<small><?php esc_html_e( 'Installed', 'logical-address-autocomplete-for-wpforms' ); ?></small>
											<?php endif; ?>
										</th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $features as $feature ) : ?>
									<tr>
										<th scope="row"><?php echo esc_html( $feature['title'] ); ?></th>
										<?php
										foreach ( $packages as $package_id => $label ) :
											// The installed column uses files; others describe standard composition.
											$has_feature  = WPFRMGEOAC_PACKAGE_TYPE === $package_id ? $feature['included'] : \in_array( $package_id, $feature['packages'], true );
											$status_label = $has_feature ? __( 'Included', 'logical-address-autocomplete-for-wpforms' ) : __( 'Not included', 'logical-address-autocomplete-for-wpforms' );
											?>
											<td class="<?php echo WPFRMGEOAC_PACKAGE_TYPE === $package_id ? 'is-current' : ''; ?>">
												<span class="<?php echo $has_feature ? 'wpfrmgeo-product-dashboard__yes' : 'wpfrmgeo-product-dashboard__no'; ?>" title="<?php echo esc_attr( $status_label ); ?>">
													<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
														<?php if ( $has_feature ) : ?>
															<path d="M5 12l4 4L19 6" />
														<?php else : ?>
															<path d="M6 6l12 12M18 6 6 18" />
														<?php endif; ?>
													</svg>
													<span class="screen-reader-text"><?php echo esc_html( $status_label ); ?></span>
												</span>
											</td>
										<?php endforeach; ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<p><?php esc_html_e( 'Google API usage and any required form-builder editions or add-ons are separate. Optional integrations may require other plugins. Feature checks show package contents, not setup or license status.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
					<a class="button button-primary" href="<?php echo esc_url( $pricing_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View packages & pricing', 'logical-address-autocomplete-for-wpforms' ); ?></a>
				</section>
			<?php elseif ( 'products' === $section ) : ?>
				<section class="wpfrmgeo-product-dashboard__card">
					<h2><?php esc_html_e( 'More tools for your WordPress site', 'logical-address-autocomplete-for-wpforms' ); ?></h2>
					<p><?php esc_html_e( 'Explore our other plugins. These descriptions are bundled locally; nothing is downloaded or installed from this page.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
				</section>
				<div class="wpfrmgeo-product-dashboard__feature-grid">
					<?php foreach ( Dashboard::get_products() as $product ) : ?>
						<article class="wpfrmgeo-product-dashboard__card wpfrmgeo-product-dashboard__product-card">
							<h2><?php echo esc_html( $product['name'] ); ?></h2>
							<p><?php echo esc_html( $product['description'] ); ?></p>
							<a class="button" href="<?php echo esc_url( $product['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Learn more', 'logical-address-autocomplete-for-wpforms' ); ?><span class="screen-reader-text"> — <?php echo esc_html( $product['name'] ); ?></span></a>
						</article>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<section class="wpfrmgeo-product-dashboard__card">
					<h2><?php esc_html_e( 'From installed to working', 'logical-address-autocomplete-for-wpforms' ); ?></h2>
					<ol>
						<li><?php esc_html_e( 'Activate WPForms and open this plugin’s native Settings screen.', 'logical-address-autocomplete-for-wpforms' ); ?></li>
						<li><?php esc_html_e( 'Enter your Google browser API key. Enable Maps JavaScript API and Places API (New), configure billing, and restrict the key to your website.', 'logical-address-autocomplete-for-wpforms' ); ?></li>
						<li><?php echo esc_html( $help_step ); ?></li>
						<li><?php esc_html_e( 'Save your form before previewing. Test the complete visitor flow.', 'logical-address-autocomplete-for-wpforms' ); ?></li>
					</ol>
					<a class="button button-primary" href="<?php echo esc_url( $settings_url ); ?>"><?php esc_html_e( 'Open Settings', 'logical-address-autocomplete-for-wpforms' ); ?></a>
					<a class="button" href="<?php echo esc_url( $docs_url . '' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Google API setup guide', 'logical-address-autocomplete-for-wpforms' ); ?></a>
				</section>
				<section class="wpfrmgeo-product-dashboard__card">
					<h2><?php esc_html_e( 'Something not working?', 'logical-address-autocomplete-for-wpforms' ); ?></h2>
					<p><?php esc_html_e( 'Check that autocomplete is enabled, then verify the browser API key, required Google services, billing and key restrictions.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
					<p><?php esc_html_e( 'Autocomplete fills an address after a visitor selects a suggestion. This free plugin does not provide current-location detection, manual geocoding or connected dynamic location fields.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
					<p><?php esc_html_e( 'Include the plugin version, field type and steps to reproduce when asking for help. Never share API keys or other credentials in a public post.', 'logical-address-autocomplete-for-wpforms' ); ?></p>
					<div class="wpfrmgeo-product-dashboard__actions">
						<a class="button" href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Browse documentation', 'logical-address-autocomplete-for-wpforms' ); ?></a>
						<a class="button" href="https://geomywp.com/support/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View support options', 'logical-address-autocomplete-for-wpforms' ); ?></a>
					</div>
				</section>
			<?php endif; ?>
		</main>
	</div>
</div>
