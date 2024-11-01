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

$is_array = ( 1 === count( $options ) ) ? '' : '[]';
$class = isset( $class ) ? $class : ''; ?>

<div class="wpq-col <?php esc_attr_e( $class ); ?> wpq-checkboxes">

	<fieldset>

		<?php view( 'contact/form', 'legend', $args ); ?>

		<?php foreach ( $options as $option ) { ?>

			<?php $checked  = isset( $option['checked'] ) ? 'checked' : ''; ?>

			<div class="wpq-col-12">

				<input
					type="checkbox"
					name="lead[fields][<?php esc_attr_e( $key ); ?>]<?php esc_attr_e( $is_array ); ?>"
					class="<?php required_class( $required ); ?> checkbox wpq-input"
					value="<?php esc_attr_e( $option['value'] ); ?>"
					<?php esc_attr_e( $checked ); ?>
					aria-label="<?php printf( '%s', esc_attr( $option['option'] ) ); ?>"
				/>

				<label><?php printf( '%s', $option['option'] ); ?></label>

			</div>

		<?php } ?>

	</fieldset>

</div>
