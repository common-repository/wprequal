<?php
/**
 * Loan Term
 *
 * Calc loan term.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$label  = get_option( 'wprequal_term_label' );
$suffix = get_option( 'wprequal_term_suffix', 'years' ); ?>

<!-- Start Loan Term -->
<div class="wpq-col-12 wpq-loan-term">
	<span class="calc-label"><?php _e( $label, 'wprequal' ); ?></span>
	<span class="calc-value"></span> <?php _e( $suffix, 'wprequal' ); ?>
	<input type="range" <?php Calc::ranges( 'term' ); ?> class="input-range calc-slider loan-term" name="lead[calc_fields][term]"
	       aria-label="<?php esc_attr_e( $label ); ?>">
</div>
<!-- End Loan Term -->