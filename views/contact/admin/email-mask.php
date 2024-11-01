<?php
/**
 * Email Mask
 *
 * Email mask select.
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$options = array(
	'no'  => 'Select One',
	'yes' => 'aaaa@aaa.aaa'
); ?>

<tr>
	<th><label><?php _e( 'Email Mask', 'wprequal' ); ?></label></th>

	<td>

		<select name="input[<?php esc_attr_e( $input['key'] ); ?>][email_mask]">
			<?php foreach ( $options as $value => $label ) { ?>
				<?php $selected = ( isset( $input['email_mask'] ) && $input['email_mask'] === $value ) ? 'selected' : ''; ?>
				<option value="<?php esc_attr_e( $value ); ?>" <?php esc_attr_e( $selected ); ?>><?php _e( $label, 'wprequal' ); ?></option>
			<?php } ?>
		</select>

	</td>

</tr>