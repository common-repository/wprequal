<?php
/**
 * Property Tax
 *
 * Calc property tax.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$label = get_option( 'wprequal_tax_label' ); ?>

<!-- Start Property Tax -->
<div class="wpq-col-12 wpq-property-tax">
	<span class="calc-label"><?php _e( $label, 'wprequal' ); ?></span>
	<?php Calc::currency( '<span class="calc-value"></span>' ); ?><?php _e( ' /year', 'wprequal' ); ?>
	<input type="range" <?php Calc::ranges( 'tax' ); ?> class="input-range calc-slider property-tax" name="lead[calc_fields][tax]"
	       aria-label="<?php esc_attr_e( $label ); ?>">
</div>
<!-- End Property Tax -->