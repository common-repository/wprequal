<?php
/**
 * Interest Rate
 *
 * Calc interest rate.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$label = get_option( 'wprequal_rate_label' ); ?>

<!-- Start Interest Rate -->
<div class="wpq-col-12 wpq-interest-rate">
	<span class="calc-label"><?php _e( $label, 'wprequal' ); ?></span>
	<span class="calc-value"></span><?php _e( '%', 'wprequal' ); ?>
	<input type="range" <?php Calc::ranges( 'rate' ); ?> class="input-range calc-slider interest-rate" name="lead[calc_fields][rate]"
	       aria-label="<?php esc_attr_e( $label ); ?>">
</div>
<!-- End Interest Rate -->