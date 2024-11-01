<?php
/**
 * Min
 *
 * Min row
 *
 * @since   7.6
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$value = empty( $input['min'] ) ? '' : $input['min']; ?>

<tr>
	<th><label><?php _e( 'Minimum', 'wprequal' ); ?></label></th>
	<td>
		<input type="number" name="input[<?php esc_attr_e( $input['key'] ); ?>][min]" value="<?php esc_attr_e( $value ); ?>" class="regular-text">
	</td>
</tr>