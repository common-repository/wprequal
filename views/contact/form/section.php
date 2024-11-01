<?php
/**
 * Section
 *
 * Section of the contact form
 *
 * @since   7.6
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<!-- Section <?php esc_html_e( $label ); ?> -->
<div class="wpq-col">
	<div class="<?php esc_attr_e( $class ); ?> section-heading"><?php esc_html_e( $label ); ?></div>
</div>
