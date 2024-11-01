<?php
/**
 * Hidden
 *
 * Hidden input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<li class="slide hidden lead-field <?php esc_attr_e( $input['key'] ); ?>">

	<?php ContactFormAdmin::view( 'head', $input ); ?>

	<table class="form-table">

		<tbody class="input-labels">

			<?php ContactFormAdmin::view( 'label', $input ); ?>
			<?php ContactFormAdmin::view( 'default-value', $input ); ?>

		</tbody>

	</table>

</li>