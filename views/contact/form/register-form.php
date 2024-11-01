<?php
/**
 * Register Form
 *
 * Register form template
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$placeholder = isset( $input['placeholder'] ) ? $input['placeholder'] : '';
$mask        = ( isset( $input['email_mask'] ) && 'yes' === $input['email_mask'] ) ? 'mask-email' : '';?>

<form class="wprequal-form register-form">

	<label for="register-email" style="display: none;"><?php esc_html_e( 'Email Address' ); ?></label>

	<input
		type="text"
		name="lead[contact][email]"
		class="<?php required_class( $required ); ?> text-input wpq-input <?php esc_attr_e( $mask ); ?>"
		placeholder="<?php esc_attr_e( $placeholder ); ?>"
		aria-label="Email"
		id="register-email"
	/>

	<button class="wprequal-submit input wpq-button">
		<?php esc_html_e( $details['button_text'] ); ?>
	</button>

	<input type="hidden" name="contact_form_id" value="<?php esc_attr_e( $contact_form_id ); ?>">
	<input type="hidden" name="social_referrer" class="social_referrer cookie">
	<input type="hidden" name="param_referrer" class="param_referrer cookie">
	<input type="hidden" name="type" value ="register"/>
	<?php do_action( 'wprequal_register_hidden_inputs' ); ?>

</form>
