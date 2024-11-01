<?php
/**
 * Inputs
 *
 * Contact form inputs
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

foreach ( $inputs as $input ) { ?>

	<div class="wpq-row">
		<?php view( 'contact/form', $input['type'], $input ); ?>
	</div>

<?php } ?>

<?php do_action( 'wprequal_before_contact_submit' ); ?>

<div class="wpq-row">

	<?php $button_action = empty( $args['button_action'] ) ? 'submit-button' : $args['button_action']; ?>

	<?php view( 'contact/form', $button_action, $details ); ?>

</div>

<?php do_action( 'wprequal_after_contact_submit' ); ?>

<?php $source_url = ( is_front_page() || is_home() ) ? home_url() : get_permalink(); ?>
<input type="hidden" name="source_url" value="<?php esc_attr_e( $source_url ); ?>">
<input type="hidden" name="contact_form_id" value="<?php esc_attr_e( $contact_form_id ); ?>">
<input type="hidden" name="social_referrer" class="social_referrer cookie">
<input type="hidden" name="param_referrer" class="param_referrer cookie">
<input type="hidden" name="type" value ="<?php esc_attr_e( $type ); ?>"/>
<?php do_action( 'wprequal_contact_hidden_inputs' ); ?>
