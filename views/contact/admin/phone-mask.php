<?php
/**
 * Phone Mask
 *
 * Phone mask select.
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
	'no'                => 'Select One',
	'(999) 999-9999'    => '(999) 999-9999',
	'(99) 9999-9999'    => '(99) 9999-9999',
	'(99[9]) 9999-9999' => '(99[9]) 9999-9999'
); ?>

<tr>
	<th><label><?php _e( 'Phone Mask', 'wprequal' ); ?></label></th>

	<td>

		<select name="input[<?php esc_attr_e( $input['key'] ); ?>][phone_mask]">
			<?php foreach ( $options as $value => $label ) { ?>
				<?php $selected = ( isset( $input['phone_mask'] ) && $input['phone_mask'] === $value ) ? 'selected' : ''; ?>
				<option value="<?php esc_attr_e( $value ); ?>" <?php esc_attr_e( $selected ); ?>><?php _e( $label, 'wprequal' ); ?></option>
			<?php } ?>
		</select>

	</td>

</tr>