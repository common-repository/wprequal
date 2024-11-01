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
} ?>

<li class="slide text lead-field <?php esc_attr_e( $input['key'] ); ?>">

	<?php ContactFormAdmin::view( 'head', $input ); ?>

	<table class="form-table">

		<tbody class="input-labels">

			<?php ContactFormAdmin::view( 'label', $input ); ?>
			<?php ContactFormAdmin::view( 'placeholder', $input ); ?>
			<?php ContactFormAdmin::view( 'required', $input ); ?>

		</tbody>

	</table>

</li>