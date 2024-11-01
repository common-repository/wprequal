<?php
/**
 * Number
 *
 * Number input
 *
 * @since   7.6
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<li class="slide number lead-field <?php esc_attr_e( $input['key'] ); ?>">

	<?php ContactFormAdmin::view( 'head', $input ); ?>

	<table class="form-table">

		<tbody class="input-labels">

			<?php ContactFormAdmin::view( 'label', $input ); ?>
			<?php ContactFormAdmin::view( 'min', $input ); ?>
			<?php ContactFormAdmin::view( 'max', $input ); ?>
			<?php ContactFormAdmin::view( 'step', $input ); ?>
			<?php ContactFormAdmin::view( 'default', $input ); ?>
			<?php ContactFormAdmin::view( 'required', $input ); ?>

		</tbody>

	</table>

</li>