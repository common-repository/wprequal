<?php
/**
 * Admin Hidden
 *
 * Admin settings hidden input.
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<input
	type="hidden"
	name="<?php printf( esc_attr__( '%s' ), $setting['key'] ); ?>"
	id="<?php printf( esc_attr__( '%s' ), $setting['key'] ); ?>"
	value="<?php printf( esc_attr__( '%s' ), $value ); ?>"
>