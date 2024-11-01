<?php
/**
 * Button
 *
 * A Input for buttons slide.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<tr class="result">

	<th><label for=""><?php _e( 'Button Text', 'wprequal' ); ?></th>
	<td>
		<input type="text" name="slide[{key}][buttons][{randID}][text]" class="regular-text">

		<i class="fas fa-minus-circle button-minus minus point" title="Remove button"></i>
	</td>

</tr>
