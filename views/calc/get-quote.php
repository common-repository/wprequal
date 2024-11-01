<?php
/**
 * Get Quote
 *
 * Get quote HTML for mortgage calculator.
 *
 * @since   7.1
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$button_text = get_option( 'wprequal_get_quote_button_text', 'Get Quote' );

$args = array(
	'contact_form_id' => (int) get_option( 'wprequal_get_quote_post_id', 0 ),
	'type'            => 'get_quote'
);?>

<div class="wpq-container get-quote">
	<button class="button"><?php _e( $button_text, 'wprequal' ); ?></button>
</div>

<div class="get-quote-contact">

	<div class="wpq-container">

		<i class="far fa-times-circle get-quote-close"></i>

		<?php if ( $msg = get_option( 'wprequal_get_quote_cta' ) ) {
			_e( $msg, 'wprequal' );
		} ?>

		<?php do_action( 'wprequal_contact_form', $args ); ?>

	</div>

</div>
