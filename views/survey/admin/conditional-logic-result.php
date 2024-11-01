<?php
/**
 * Conditional Logic Result
 *
 * Conditional logic result inputs.
 *
 * @since   7.0
 *
 * @package WPrequal
 */
namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$name = 'slide[{key}][logic][conditions][{randID}]'; ?>

<tr class="result">

	<td colspan="4" class="logic">

		<select name="<?php esc_attr_e( $name ); ?>[logic_option]" class="logic-option">
			<option value=""><?php _e( 'Select One', 'wprequal' ); ?></option>
		</select>

		<select name="<?php esc_attr_e( $name ); ?>[logic_equal]" class="logic-equal">
			<option value="="><?php _e( 'is equal to', 'wprequal' ); ?></option>
			<option value="!="><?php _e( 'is not equal to', 'wprequal' ); ?></option>
			<option value=">"><?php _e( 'is greater than', 'wprequal' ); ?></option>
			<option value="<"><?php _e( 'is less than', 'wprequal' ); ?></option>
			<option value=">="><?php _e( 'is greater than or equal to', 'wprequal' ); ?></option>
			<option value="<="><?php _e( 'is less than or equal to', 'wprequal' ); ?></option>
		</select>

		<input type="text" name="<?php esc_attr_e( $name ); ?>[logic_value]" class="logic-value wpq-required">

		<i class="fas fa-minus-circle logic-minus minus point" title="Remove logic condition"></i>

	</td>

</tr>