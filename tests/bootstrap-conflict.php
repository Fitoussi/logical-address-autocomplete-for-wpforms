<?php
/** Isolated lifecycle ownership tests: premium before or after free bootstrap. */
if ( isset( $argv[1] ) ) {
 define( 'ABSPATH', __DIR__ );
 define( 'WPFORMS_VERSION', '2.0.0' );
 define( 'WPFRMGEOAC_MIN_HOST_VERSION', '1.9.4.1' );
 $GLOBALS['hooks'] = [];
 function add_action( $hook, $callback, $priority = 10 ) { $GLOBALS['hooks'][] = [ $hook, $callback, $priority ]; }
 require __DIR__ . '/../src/Loader.php';
 if ( 'before' === $argv[1] ) { eval( 'class Geolocation_For_WPForms {}' ); }
 // Both plugin entry points finish loading before plugins_loaded runs.
 if ( 'after' === $argv[1] ) { eval( 'class Geolocation_For_WPForms {}' ); }
 WPFormsGeolocationAutocomplete\Loader::load();
 if ( count( $GLOBALS['hooks'] ) !== 2 || 'admin_notices' !== $GLOBALS['hooks'][0][0] ) { exit( 1 ); }
 echo "Premium wins: {$argv[1]}\n";
 exit;
}
foreach ( [ 'before', 'after' ] as $order ) {
 passthru( escapeshellarg( PHP_BINARY ) . ' ' . escapeshellarg( __FILE__ ) . ' ' . escapeshellarg( $order ), $code );
 if ( $code ) { exit( $code ); }
}
