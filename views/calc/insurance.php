<?php
/**
 * Insurance
 *
 * Calc insurance.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$label = get_option( 'wprequal_insurance_label' ); ?>

<!-- Start Insurance -->
<div class="wpq-col-12 wpq-insurance">
	<span class="calc-label"><?php _e( $label, 'wprequal' ); ?></span>
	<?php Calc::currency( '<span class="calc-value"></span>' ); ?><?php _e( ' /year', 'wprequal' ); ?>
	<input type="range" <?php Calc::ranges( 'insurance' ); ?> class="input-range calc-slider insurance" name="lead[calc_fields][insurance]"
	       aria-label="<?php esc_attr_e( $label ); ?>">
</div>
<!-- End Insurance -->