<?php
/**
 * Options
 *
 * Options row
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<tr class="contact-options">
	<th><label><?php _e( 'Options', 'wprequal' ); ?></label></th>
</tr><?php

$default = array( 0 => array( 'option' => '', 'value' => '' ) );
$options = empty ( $input['options'] ) ? $default : $input['options'];
$i       = 1;

foreach ( $options as $key => $option ) {

	$checked = isset( $option['checked'] ) ? 'checked' : ''; ?>

	<tr class="input-option">

		<td colspan="3">

			<input type="<?php esc_attr_e( $input['input'] ); ?>" name="input[<?php esc_attr_e( $input['key'] ); ?>][options][<?php esc_attr_e( $i ); ?>][checked]" value="checked" class="<?php esc_attr_e( $input['input'] ); ?>" <?php esc_attr_e( $checked ); ?>>

			<input type="text" name="input[<?php esc_attr_e( $input['key'] ); ?>][options][<?php esc_attr_e( $i ); ?>][option]" value="<?php esc_attr_e( $option['option'] ); ?>" class="regular-text wpq-required option" placeholder="Option">

			<input type="text" name="input[<?php esc_attr_e( $input['key'] ); ?>][options][<?php esc_attr_e( $i ); ?>][value]" value="<?php esc_attr_e( $option['value'] ); ?>" class="regular-text value" placeholder="Value">

			<i class="fas fa-plus-circle add-input point"></i><i class="fas fa-minus-circle trash-input point red"></i>

			<input type="hidden" class="key" value="<?php esc_attr_e( $input['key'] ); ?>"/>
		</td>

	</tr>

	<?php $i++; ?>

<?php } ?>