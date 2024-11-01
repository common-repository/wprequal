<?php
/**
 * Amount
 *
 * Inputs for amount slide.
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

	<tr>
		<th><label for=""><?php _e( 'Currency', 'wprequal' ); ?></label></th>
		<td>
			<?php $symbols = Core::currency_symbols(); ?>

			<select name="slide[{key}][currency_symbol]" class="widefat">

				<option value="-1"><?php _e( 'Select One', 'wprequal' ); ?></option>

				<?php foreach ( $symbols as $value => $symbol ) { ?>
					<option value="<?php esc_attr_e( $value ); ?>"><?php _e( $symbol, 'wprequal' ); ?></option>
				<?php } ?>

			</select>

		</td>

		<th><label for=""><?php _e( 'Currency Placement', 'wprequal' ); ?></label></th>
		<td>
			<select name="slide[{key}][currency_placement]" class="widefat">
				<option value="-1"><?php _e( 'Select One', 'wprequal' ); ?></option>
				<option value="before"><?php _e( 'Before Amount', 'wprequal' ); ?></option>
				<option value="after"><?php _e( 'After Amount', 'wprequal' ); ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<th><label for=""><?php _e( 'Default Amount', 'wprequal' ); ?></label></th>
		<td><input type="number" step=".001" name="slide[{key}][default_amount]" class="regular-text wpq-required"></td>

		<th><label for=""><?php _e( 'Step Amount', 'wprequal' ); ?></label></th>
		<td><input type="number" step=".001" name="slide[{key}][step_amount]" class="regular-text wpq-required"></td>
	</tr>

	<tr>
		<th><label for=""><?php _e( 'Minimum Amount', 'wprequal' ); ?></label></th>
		<td><input type="number" step=".001" name="slide[{key}][min_amount]" class="regular-text wpq-required"></td>

		<th><label for=""><?php _e( 'Maximum Amount', 'wprequal' ); ?></label></th>
		<td><input type="number" step=".001" name="slide[{key}][max_amount]" class="regular-text wpq-required"></td>
	</tr>

</tbody>

<?php view( 'survey/admin', 'slide-end' ); ?>
