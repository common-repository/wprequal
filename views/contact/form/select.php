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

	<?php view( 'contact/form', 'label', $args ); ?>

	<select
		name="lead[fields][<?php esc_attr_e( $key ); ?>]"
		class="<?php required_class( $required ); ?> select wpq-input"
		aria-label="<?php esc_attr_e( $label ); ?>"
	>

		<?php foreach ( $options as $option ) { ?>

			<?php $selected = isset( $option['checked'] ) ? 'selected' : ''; ?>

			<option value="<?php esc_attr_e( $option['value'] ); ?>" <?php esc_attr_e( $selected ); ?>><?php printf( '%s', $option['option'] ); ?></option>

		<?php } ?>

	</select>

</div>