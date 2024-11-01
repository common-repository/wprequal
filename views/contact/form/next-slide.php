<?php
/**
 * Next Slide Button
 *
 * Next slide button
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="wpq-col wpq-p-4">
	<?php printf( '<button class="next-slide button">%s</button>', esc_html( $button_text ) ); ?>
</div>
