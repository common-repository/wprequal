<?php
/**
 * Reset Defaults
 *
 * Reset all plugin settings to default values.
 *
 * @since   6.4.1
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

add_action( 'admin_init', 'WPrequal\reset_defaults' );

/**
 * Reset plugin defaults
 */

function reset_defaults() {

	if ( is_admin() && current_user_can( WPREQUAL_CAP ) && isset( $_GET['wprequal_reset_defaults'] ) ) {

		$options = options();

		foreach ( $options as $option => $value ) {
			update_option( $option, $value );
		}

	}

}
