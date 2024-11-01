<?php
/**
 * Checkbox
 *
 * Checkbox input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$input['input'] = 'checkbox'; ?>

<li class="slide checkbox lead-field <?php esc_attr_e( $input['key'] ); ?>">

	<?php ContactFormAdmin::view( 'head', $input ); ?>

	<table class="form-table">

		<tbody class="input-rows">

			<?php ContactFormAdmin::view( 'label', $input ); ?>
			<?php ContactFormAdmin::view( 'required', $input ); ?>

		</tbody>

		<tbody class="input-options">

			<?php ContactFormAdmin::view( 'options', $input ); ?>

		</tbody>

	</table>

</li>