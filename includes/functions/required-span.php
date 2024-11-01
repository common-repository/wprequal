<?php
/**
 * Required Span
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
 * Required Span
 *
 * Print the required span.
 *
 * @param string $required
 *
 * @return string
 */

function required_span( $required ) {

	if ( empty( $required ) ) {
		return '';
	}

	return '<span class="red">*</span>';

}
