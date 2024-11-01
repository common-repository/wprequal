<?php
/**
 * Register Shortcode
 *
 * Shortcode Meta Box
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$post_id = get_the_ID(); ?>

<div class="shortcode">
	<code><?php esc_attr_e( "[wprequal_register_form post_id={$post_id}]" ); ?></code>
</div>