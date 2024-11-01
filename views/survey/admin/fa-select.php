<?php
/**
 * FA Select
 *
 * Inputs for icons slide.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<tr class="fa-select-row">

	<td>
		<span>
			<select name="slide[{key}][icons][{randID}][class]" class="fa-select"></select>
		</span>
	</td>

	<td>
		<input type="text" name="slide[{key}][icons][{randID}][text]" class="large-text" placeholder="Icon Text">
	</td>

	<td>
		<span><i class="fas fa-minus-circle point minus-fa-row"></i></span>
	</td>
</tr>
