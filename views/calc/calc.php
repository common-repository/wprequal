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
<form class="wprequal-calc wprequal-form <?php esc_attr_e( $size ); ?> <?php esc_attr_e( $shade ); ?>">

	<div class="wpq-container">

		<div class="wpq-row"><?php

			Calc::view( 'title', 'wprequal_calc_title' );
			Calc::view( 'purchase-price' );
			Calc::view( 'down-payment' );
			Calc::view( 'loan-term' );
			Calc::view( 'interest-rate' );
			if ( empty( $size ) ) {
				Calc::view( 'property-tax' );
				Calc::view( 'insurance' );
			}
			Calc::view( 'payment' ); ?>

		</div>

		<?php if ( Core::status( 2 ) && Settings::is_checked( 'wprequal_show_get_quote' ) ) {
			Calc::view( 'get-quote' );
		} ?>

	</div>

</form>
<!-- End Calc Section -->