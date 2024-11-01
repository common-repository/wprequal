<?php
/**
 * Form
 *
 * Form for prequal widget.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<p><label for="size"><?php _e( 'Survey Form', 'wprequal' ); ?></label>
<p><?php wp_dropdown_pages( $args ); ?></p>
