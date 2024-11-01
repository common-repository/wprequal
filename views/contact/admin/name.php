<?php
/**
 * Name
 *
 * Full name input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$fname = empty( $input['labels']['fname'] ) ? 'First' : $input['labels']['fname'];
$lname = empty( $input['labels']['lname'] ) ? 'Last' : $input['labels']['lname'];
$input['label'] = __( 'Full Name', 'wprequal' ); ?>

<li class="slide name <?php esc_attr_e( $input['key'] ); ?> full-name">

	<?php ContactFormAdmin::view( 'head', $input ); ?>

	<table class="form-table">

		<tbody class="input-labels">

			<tr>
				<th><label><?php _e( 'First Name Label', 'wprequal' ); ?></label></th>
				<td>
					<input type="text" name="input[<?php esc_attr_e( $input['key'] ); ?>][labels][fname]" value="<?php esc_attr_e( $fname ); ?>" class="input-label regular-text wpq-required first-name">
				</td>

				<th><label><?php _e( 'Last Name Label', 'wprequal' ); ?></label></th>
				<td>
					<input type="text" name="input[<?php esc_attr_e( $input['key'] ); ?>][labels][lname]" value="<?php esc_attr_e( $lname ); ?>" class="input-label regular-text wpq-required last-name">
				</td>
			</tr>

			<?php ContactFormAdmin::view( 'required', $input ); ?>

		</tbody>

	</table>

</li>