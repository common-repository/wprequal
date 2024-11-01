<?php
/**
 * Body
 *
 * Cac body.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<!-- Start Calc Section -->
<form class="wprequal-calc focus wprequal-form <?php esc_attr_e( $shade ); ?>">

	<div class="wpq-container">

		<div class="wpq-row">

			<div class="wpq-col-md-6">

				<?php Calc::view( 'title', 'wprequal_focus_title' ); ?>

				<div class="wpq-inputs">

					<?php Calc::view( 'purchase-price' );
					Calc::view( 'down-payment' );
					Calc::view( 'loan-term' );
					Calc::view( 'interest-rate' );
					if ( empty( $size ) ) {
						Calc::view( 'property-tax' );
						Calc::view( 'insurance' );
					} ?>

				</div>

			</div>

			<div class="wpq-col-md-6">

				<div class="wpq-results">

					<?php Calc::view( 'payment' ); ?>

					<?php if ( $msg = get_option( 'wprequal_focus_msg' ) ) { ?>
						<div class="wpq-msg">
							<?php printf( '%s', $msg ); ?>
						</div>
					<?php } ?>

					<div class="wpq-align-self-end">
						<?php if ( Core::status( 2 ) && Settings::is_checked( 'wprequal_show_get_quote' ) ) {
							Calc::view( 'get-quote' );
						} ?>
					</div>

				</div>

			</div>

		</div>

	</div>

</form>
<!-- End Calc Section -->
