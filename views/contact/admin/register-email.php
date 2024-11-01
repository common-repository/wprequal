<?php
/**
 * Register Email
 *
 * Register email input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<table class="form-table">

	<tbody class="input-labels">

		<?php view( RegisterFormAdmin::view_path, 'label', [ 'input' => $input ] ); ?>
		<?php view( RegisterFormAdmin::view_path, 'placeholder', [ 'input' => $input ] ); ?>
		<?php view( RegisterFormAdmin::view_path, 'email-mask', [ 'input' => $input ] ); ?>
		<?php view( RegisterFormAdmin::view_path, 'required', [ 'input' => $input ] ); ?>

	</tbody>

</table>
