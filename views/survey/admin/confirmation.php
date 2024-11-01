<?php
/**
 * Confirmation
 *
 * Inputs for confiramtion message slide.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<?php view( 'survey/admin', 'slide-start' ); ?>

<tbody>

	<tr class="confirmation">

		<th scope="row">
			<label for="confirmation-msg"><?php _e( 'Confirmation Message', 'wprequal' ); ?></label>
		</th>

		<td colspan="3">
			<textarea name="slide[{key}][editor]" class="editor-{key} large-text" rows="5"></textarea>
		</td>

	</tr>

</tbody>

<?php view( 'survey/admin', 'slide-end' ); ?>
