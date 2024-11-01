<?php
/**
 * Placeholder
 *
 * Placeholder row
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<tr>
	<th><label><?php _e( 'Placeholder', 'wprequal' ); ?></label></th>
	<td>
		<input type="text" name="input[<?php esc_attr_e( $input['key'] ); ?>][placeholder]" value="<?php esc_attr_e( $input['placeholder'] ); ?>" class="regular-text">
	</td>
</tr>