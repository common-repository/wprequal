<?php
/**
 * Conditional Logic
 *
 * Conditional logic inputs.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<tbody>

	<tr class="conditional-logic-heading">

		<th>
			<label><?php _e( 'Conditional Logic', 'wprequal' ); ?></label>
		</th>

		<td>
			<input type="checkbox" name="slide[{key}][check][conditional_logic]" class="conditional-checkbox" value="checked">
		</td>

	</tr>

</tbody>

<tbody class="conditional-logic hide">

	<tr>

		<td colspan="4">

			<select name="slide[{key}][logic][show_hide]">
				<option value="show"><?php _e( 'Show', 'wprequal' ); ?></option>
				<option value="hide"><?php _e( 'Hide', 'wprequal' ); ?></option>
			</select>

			<?php _e( ' if ', 'wprequal' ); ?>

			<select name="slide[{key}][logic][any_all]">
				<option value="any"><?php _e( 'any', 'wprequal' ); ?></option>
				<option value="all"><?php _e( 'all', 'wprequal' ); ?></option>
			</select>

			<?php _e( ' conditions match.', 'wprequal' ); ?>

		</td>

	</tr>

	<tr>

		<td>
			<b><?php _e( 'Add Condition:', 'wprequal' ); ?></b>
			<i class="fas fa-plus-circle logic-add point" title="Add logic condition"></i>
		</td>

	</tr>

	<?php view( 'survey/inputs', 'conditional-logic-result' ); ?>

</tbody>