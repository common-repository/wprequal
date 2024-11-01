<?php
/**
 * Form Phone
 *
 * Form phone input
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$mask = isset( $phone_mask )  ? $phone_mask  : '';
$class = isset( $class ) ? $class : ''; ?>

<div class="wpq-col <?php esc_attr_e( $class ); ?>">

	<?php view( 'contact/form', 'label', $args ); ?>

	<input
		type="text"
		name="lead[contact][phone]"
		class="<?php required_class( $required ); ?> text-input wpq-input mask-phone"
		placeholder="<?php esc_attr_e( $placeholder ); ?>"
		<?php if ( ! empty( $mask ) && 'no' !== $mask ) { ?>
			data-phone-mask="<?php esc_attr_e( $mask ); ?>"
		<?php } ?>
		aria-label="Phone"
	/>
</div>
