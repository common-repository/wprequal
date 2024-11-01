<?php
/**
 * Step
 *
 * Step row
 *
 * @since   7.6
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$value = empty( $input['step'] ) ? '' : $input['step']; ?>

<tr>
	<th><label><?php _e( 'Step', 'wprequal' ); ?></label></th>
	<td>
		<input type="number" name="input[<?php esc_attr_e( $input['key'] ); ?>][step]" step=".01" value="<?php esc_attr_e( $value ); ?>" class="regular-text">
	</td>
</tr>