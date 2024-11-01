<?php
/**
 * Required Class
 *
 * Load view file.
 *
 * @since   7.9.3
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

/**
 * Required Class
 *
 * Print the required class.
 *
 * @param string $required
 */

function required_class( $required ) {

	if ( 'checked' === $required ) {
		esc_attr_e( 'wpq-required' );
	}

}
