<?php
/**
 * Admin Number
 *
 * Admin settings input.
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<td>

	<input
		type="number"
		name="<?php printf( esc_attr__( '%s' ), $setting['key'] ); ?>"
		id="<?php printf( esc_attr__( '%s' ), $setting['key'] ); ?>"
		value="<?php printf( esc_attr__( '%s' ), $value ); ?>"
		class="<?php printf( esc_attr__( '%s' ), $setting['class'] ); ?>"
		placeholder="<?php printf( esc_attr__( '%s' ), $setting['placeholder'] ); ?>"
		min="<?php printf( esc_attr__( '%s' ), $setting['min'] ); ?>"
		max="<?php printf( esc_attr__( '%s' ), $setting['max'] ); ?>"
		step="<?php printf( esc_attr__( '%s' ), $setting['step'] ); ?>"
	>

</td>