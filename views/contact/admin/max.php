<?php
/**
 * Max
 *
 * Max row
 *
 * @since   7.6
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$value = empty( $input['max'] ) ? '' : $input['max']; ?>

<tr>
	<th><label><?php _e( 'Maximum', 'wprequal' ); ?></label></th>
	<td>
		<input type="number" name="input[<?php esc_attr_e( $input['key'] ); ?>][max]" value="<?php esc_attr_e( $value ); ?>" class="regular-text">
	</td>
</tr>