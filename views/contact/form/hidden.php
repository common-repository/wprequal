<?php
/**
 * Hidden
 *
 * Hidden input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="wpq-hidden">
	<input
		type="hidden"
		name="lead[fields][<?php esc_attr_e( $key ); ?>]"
		value="<?php esc_attr_e( $default_value ); ?>"
	/>
</div>
