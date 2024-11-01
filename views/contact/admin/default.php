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

$value = empty( $input['default'] ) ? '' : $input['default']; ?>

<tr>
	<th><label><?php _e( 'Default Value', 'wprequal' ); ?></label></th>
	<td>
		<input type="number" name="input[<?php esc_attr_e( $input['key'] ); ?>][default]" value="<?php esc_attr_e( $value ); ?>" class="regular-text">
	</td>
</tr>