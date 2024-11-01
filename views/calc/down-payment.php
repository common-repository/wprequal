<?php
/**
 * Down Payment
 *
 * Calc down payment.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$label = get_option( 'wprequal_down_payment_label' ); ?>

<!-- Start Down Payment -->
<div class="wpq-col-12 wpq-down-payment">
	<span class="calc-label"><?php _e( $label, 'wprequal' ); ?></span>
	<?php Calc::currency( '<span class="calc-value"></span>' ); ?>
	<input type="range" <?php Calc::ranges( 'down_payment' ); ?> class="input-range calc-slider down-payment" name="lead[calc_fields][down_payment]"
	       aria-label="<?php esc_attr_e( $label ); ?>">
</div>
<!-- End Down Payment -->