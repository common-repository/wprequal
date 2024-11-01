<?php
/**
 * Text
 *
 * Inputs for text slide.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<?php view( 'survey/admin', 'slide-start' ); ?>

<tbody>

	<tr class="zipcode-mask">

		<th scope="row">
			<label for="zipcode-mask"><?php _e( 'Input Mask', 'wprequal' ); ?></label>
		</th>

		<td>

			<select name="slide[{key}][input_mask]" id="zipcode-mask" class="input-mask">
				<option><?php _e( 'None', 'wprequal' ); ?></option>
				<option value="99999"><?php _e( 'US Zip Code 99999', 'wprequal' ); ?></option>
				<option value="a9a-9a9"><?php _e( 'Canada Postal Code a9a-9a9', 'wprequal' ); ?></option>
				<option value="9999"><?php _e( '9999', 'wprequal' ); ?></option>
				<option value="99999[-9999]"><?php _e( '99999[-9999]', 'wprequal' ); ?></option>
			</select>

		</td>

	</tr>

</tbody>

<?php view( 'survey/admin', 'slide-end' ); ?>
