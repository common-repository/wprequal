<?php
/**
 * Form Submit Button
 *
 * Form submit button
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="wpq-col wpq-p-4">
	<?php printf( '<button class="wprequal-submit button" aria-label="Submit">%s</button>', esc_html( $button_text ) ); ?>
</div>
