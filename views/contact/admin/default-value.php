<?php
/**
 * Default Value
 *
 * Default Value input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$value = isset( $input['default_value'] ) ? $input['default_value'] : ''; ?>

<tr>
	<th><label><?php _e( 'Default Value', 'wprequal' ); ?></label></th>
	<td>
		<input type="text" name="input[<?php esc_attr_e( $input['key'] ); ?>][default_value]" value="<?php esc_attr_e( $value ); ?>" class="regular-text">
	</td>
</tr>