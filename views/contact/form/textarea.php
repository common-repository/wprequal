<?php
/**
 * Text
 *
 * Text input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$class = isset( $class ) ? $class : ''; ?>

<div class="wpq-col <?php esc_attr_e( $class ); ?>">

	<?php view( 'contact/form', 'label', $args ); ?>

	<textarea
		name="lead[fields][<?php esc_attr_e( $key ); ?>]"
		class="<?php required_class( $required ); ?> text-input wpq-input"
		placeholder="<?php esc_attr_e( $placeholder ); ?>"
		aria-label="<?php esc_attr_e( $label ); ?>"
	></textarea>
</div>
