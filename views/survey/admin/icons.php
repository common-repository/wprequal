<?php
/**
 * Icons
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

<?php view( 'survey/admin', 'slide-start' ); ?>

<tbody>

	<tr>
		<th>
			<label for=""><?php _e( 'Add Icon', 'wprequal' ); ?></label>
			<i class="fas fa-plus-circle point add-fa-row" title="Add Icon"></i>
		</th>

		<?php $fa_license = SurveyFormAdmin::fa_license(); ?>
		<?php view( 'buttons', "$fa_license-fa-icons" ); ?>

	</tr>

</tbody>

<tbody class="fa-select-tbody"></tbody>

<?php view( 'survey/admin', 'slide-end' ); ?>
