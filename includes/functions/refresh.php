<?php
/**
 * Refresh
 *
 * Refresh the plugin settings.
 *
 * @since   1.0.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

add_action( 'init', 'WPrequal\refresh' );

function refresh() {
	if ( isset( $_GET['wprequal_refresh'] ) ) {
		clear_transients();
	}
}
