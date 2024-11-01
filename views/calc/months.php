<?php
/**
 * Months
 *
 * Calc months.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$label = get_option( 'wprequal_amortize_label' ); ?>

<!-- Start Months -->
<div class="wpq-col-12 wpq-months">
	<span><?php _e( $label, 'wprequal' ); ?></span>
	<input type="range" min="1" value="1" step="1" class="calc-slider months" aria-label="<?php esc_attr_e( $label ); ?>">
</div>
<!-- End Months -->