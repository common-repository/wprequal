<?php
/**
 * Form Comments
 *
 * Form comments input
 *
 * @since   7.0
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
		name="lead[contact][comments]"
		class="<?php required_class( $required ); ?> textarea wpq-input"
		placeholder="<?php esc_attr_e( $placeholder ); ?>"
		<?php printf( '%s', $label ); ?>
	></textarea>
</div>