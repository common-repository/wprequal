<?php
/**
 * Required
 *
 * Required checkbox
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
	<th><label><?php _e( 'Required', 'wprequal' ); ?></label></th>
	<td>
		<input type="checkbox" name="input[<?php esc_attr_e( $input['key'] ); ?>][required]" value="checked" <?php esc_attr_e( $input['required'] ); ?>>
	</td>
</tr>

