<?php
/**
 * Contact
 *
 * Inputs for contact slide.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$args = [
	'post_type'        => 'wpq_contact_form',
	'name'             => 'slide[{key}][contact_form_id]',
	'class'            => 'contact_form_id regular-text wpq-required',
	'id'               => 'contact_form_id',
	'show_option_none' => __( 'Select One', 'wprequal' )
]; ?>

<?php view( 'survey/admin', 'slide-start' ); ?>

<tbody>

	<tr>

		<th scope="row"><label for="contact-form-id"><?php _e( 'Contact Form', 'wprequal' ); ?></label></th>

		<td><?php wp_dropdown_pages( $args ); ?></td>

		<th scope="row"><label for="button-action"><?php _e( 'Button Action', 'wprequal' ); ?></label></th>

		<td>
			<select id="button-action" name="slide[{key}][button_action]" class="widefat">
				<option value="<?php esc_attr_e( 'submit-button' ); ?>"><?php _e( 'Form Submit', 'wprequal' ); ?></option>
				<option value="<?php esc_attr_e( 'next-slide' ); ?>"><?php _e( 'Next Slide', 'wprequal' ); ?></option>
			</select>

		</td>

	</tr>

</tbody>

<?php view( 'survey/admin', 'slide-end' ); ?>
