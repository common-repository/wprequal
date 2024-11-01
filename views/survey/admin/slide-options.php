<?php
/**
 * Form Options
 *
 * Form option buttons
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="survey slide-options">
	<button class="option" data-id="icons"><?php _e( 'Icons', 'wprequal' ); ?></button>
	<button class="option" data-id="text"><?php _e( 'Text Input', 'wprequal' ); ?></button>
	<button class="option" data-id="buttons"><?php _e( 'Buttons', 'wprequal' ); ?></button>
	<button class="option" data-id="amount"><?php _e( 'Amount Slider', 'wprequal' ); ?></button>
	<button class="option" data-id="contact"><?php _e( 'Contact Form', 'wprequal' ); ?></button>
	<button class="option" data-id="processing"><?php _e( 'Processing', 'wprequal' ); ?></button>
	<button class="option" data-id="confirmation"><?php _e( 'Confirmation Message', 'wprequal' ); ?></button>
	<button class="option" data-id="redirect"><?php _e( 'Redirect', 'wprequal' ); ?></button>
</div>