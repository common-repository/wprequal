<?php
/**
 * Range
 *
 * Range input
 *
 * @since   7.6
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$args['randID'] = $randID = 'range-' . rand( 1, 99999 );
$class = isset( $class ) ? $class : ''; ?>

<div class="wpq-col <?php esc_attr_e( $class ); ?> range-wrap">

	<?php view( 'contact/form', 'output', $args ); ?>

	<input
		id="<?php esc_attr_e( $randID ); ?>"
		type="range"
		name="lead[fields][<?php esc_attr_e( $key ); ?>]"
		class="<?php required_class( $required ); ?> range-input wpq-input"
		min="<?php esc_attr_e( $min ); ?>"
		max="<?php esc_attr_e( $max ); ?>"
		step="<?php esc_attr_e( $step ); ?>"
		value="<?php esc_attr_e( $default ); ?>"
		aria-label="<?php esc_attr_e( $label ); ?>"
	/>
</div>
