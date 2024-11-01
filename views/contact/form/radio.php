<?php
/**
 * Radio
 *
 * Radio input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$class = isset( $class ) ? $class : '';?>

<div class="wpq-col <?php esc_attr_e( $class ); ?>">

	<fieldset>

		<?php view( 'contact/form', 'legend', $args ); ?>

		<?php foreach ( $options as $option ) { ?>

			<?php $checked = isset( $option['checked'] ) ? 'checked' : ''; ?>

			<div class="wpq-col-12">

				<input
					type="radio"
					name="lead[fields][<?php esc_attr_e( $key ); ?>]"
					class="<?php required_class( $required ); ?> radio-button wpq-input"
					value="<?php esc_attr_e( $option['value'] ); ?>"
					<?php esc_attr_e( $checked ); ?>
					aria-label="<?php esc_attr_e( $label ); ?>"
				/>

				<label><?php printf( '%s', $option['option'] ); ?></label>

			</div>

		<?php } ?>

	</fieldset>

</div>
