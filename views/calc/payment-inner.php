<?php
/**
 * Payment inner
 *
 * Calc payment inner.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<!-- Start Payment Inner -->
<div class="calc-payment-row" aria-label="Payment Amount">

	<?php $before = apply_filters( 'wprequal_before_calc_payment', 'Payment: ' ); ?>
	<span class="per-month"><?php _e( $before, 'wprequal' ); ?></span>

	<?php Calc::currency( '<span class="calc-payment-amount"></span>' ); ?>

	<?php $after = apply_filters( 'wprequal_after_calc_payment', ' /month' ); ?>
	<span class="per-month"><?php _e( $after, 'wprequal' ); ?></span>

</div>
<!-- End Payment Inner -->