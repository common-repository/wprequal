<?php
/**
 * Label
 *
 * Label row
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$label = empty( $input['label'] ) ? '' : $input['label']; ?>

<tr>
	<th><label><?php _e( 'Label', 'wprequal' ); ?></label></th>
	<td>
		<input type="text" name="input[<?php esc_attr_e( $input['key'] ); ?>][label]" value="<?php esc_attr_e( $label ); ?>" class="input-label regular-text wpq-required">
	</td>
</tr>