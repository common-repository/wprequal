<?php
/**
 * Contact info
 *
 * Contact Info Meta Box
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="lead-fields">

	<table class="form-table">

		<tbody>

			<?php foreach ( $values as $key => $value ) { ?>

				<?php $value = empty( $value )        ? ''        : $value; ?>
				<?php $label = empty( $labels[$key] ) ? 'Unknown' : $labels[$key]; ?>

				<tr>

					<th>
						<label for=""><?php _e( $label, 'wprequal' ); ?></label>
					</th>

					<td>

						<?php if ( Core::status( 1 ) ) { ?>

							<input
								type="text"
								name="fields[<?php esc_attr_e( $key ); ?>]"
								id="<?php esc_attr_e( $key ); ?>"
								value="<?php esc_attr_e( $value ); ?>"
								class="regular-text"
							>

					<?php } else {

							view( 'buttons', 'go-premium' );

					}  ?>

					</td>

				</tr>

			<?php } ?>

		</tbody>

	</table>

</div>