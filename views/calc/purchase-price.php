<?php
/**
 * Purchase Price
 *
 * Calc purchase price.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$label = get_option( 'wprequal_price_label' ); ?>

<!-- Start Purchase Price -->
<div class="wpq-col-12 wpq-purchase-price">
	<span class="calc-label"><?php _e( $label, 'wprequal' ); ?></span>
	<?php Calc::currency( '<span class="calc-value"></span>' ); ?>
	<input type="range" <?php Calc::ranges( 'price' ); ?> class="input-range calc-slider purchase-price" name="lead[calc_fields][price]"
	       aria-label="<?php esc_attr_e( $label ); ?>">
</div>
<!-- End Purchase Price -->