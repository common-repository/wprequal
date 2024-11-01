<?php
/**
 * Text Input
 *
 * Text input for survey form.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="text wpq-col-12">

	<input type="text" name="lead[fields][{key}]" id="{key}" class="text-input">

</div>

<div class="wpq-col-12">

	<div class="button next-slide wpq-validate-text"><?php _e( 'Next', 'wprequal' ); ?></div>

</div>
