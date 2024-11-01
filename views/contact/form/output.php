<?php
/**
 * Output
 *
 * Output
 *
 * @since   7.6
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="label">
	<?php printf( '%s%s', esc_html( $label ), required_span( $required ) ); ?>
	<?php printf( '<output class="output" for="%s">%s</output>', esc_attr( $randID ), esc_html( $default ) ); ?>
</div>

