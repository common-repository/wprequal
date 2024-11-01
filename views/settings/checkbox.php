<?php
/**
 * Admin Checkbox
 *
 * Admin settings checkbox.
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
		type="checkbox"
		value="checked"
		name="<?php printf( esc_attr__( '%s' ), $setting['key'] ); ?>"
		id="<?php printf( esc_attr__( '%s' ), $setting['key'] ); ?>"
		class="<?php printf( esc_attr__( '%s' ), $setting['class'] ); ?>"
		<?php printf( esc_attr__( '%s' ), $value ); ?>
	>
</td>