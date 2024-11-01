<?php
/**
 * Section
 *
 * Section input
 *
 * @since   7.6
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<li class="slide section lead-field <?php esc_attr_e( $input['key'] ); ?>">

	<?php ContactFormAdmin::view( 'head', $input ); ?>

	<table class="form-table">

		<tbody class="input-labels">

			<?php ContactFormAdmin::view( 'label', $input ); ?>

		</tbody>

	</table>

</li>