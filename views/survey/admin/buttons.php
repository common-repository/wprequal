<?php
/**
 * Buttons
 *
 * Inputs for buttons slide.
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

	<tr>
		<th>
			<label for=""><?php _e( 'Add Button', 'wprequal' ); ?></label>
			<i class="fas fa-plus-circle point add-button" title="Add Button"></i>
		</th>

	</tr>

</tbody>

<tbody class="button-tbody"></tbody>

<?php view( 'survey/admin', 'slide-end' ); ?>
