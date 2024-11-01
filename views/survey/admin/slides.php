<?php
/**
 * Slides
 *
 * Slides
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

global $post;

if ( ! $slides = get_post_meta( $post->ID, 'slides', TRUE ) ) { ?>
	<div class="no-slides"><?php _e( 'Please add at least 1 silde.', 'wprequal' ); ?></div>
<?php } ?>

<ul class="survey-slides"></ul>

<div><b><?php _e( 'Note:', 'wprequal' ); ?></b></div>
<div><?php _e( 'All survey forms must end with a contact form with Lead Fields, processing slide, and confirmation message or redirect.', 'wprequal' ); ?></div>
<div><?php _e( 'Only 1 contact form with contact fields can be submitted per request. Use contact forms with Text Input lead fields instead.', 'wprequal' ); ?></div>

