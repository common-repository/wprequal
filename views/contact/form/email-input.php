<?php
/**
 * Form Email Input
 *
 * Form email input
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$mask     = ( isset( $email_mask ) && 'yes' === $email_mask ) ? 'mask-email' : ''; ?>

<input
	type="text"
	name="lead[contact][email]"
	class="<?php required_class( $required ); ?> text-input wpq-input <?php esc_attr_e( $mask ); ?>"
	placeholder="<?php esc_attr_e( $placeholder ); ?>"
	aria-label="Email"
/>