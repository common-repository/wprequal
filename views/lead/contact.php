<?php
/**
 * Contact info
 *
 * Contact Info Meta Box
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

extract( $values );

wp_nonce_field( 'save_lead', 'save_lead_nonce' ); ?>

<div class="contact-info">

	<table class="form-table">

		<tbody>

			<tr>
				<th>
					<label for="fname"><?php _e( 'First Name', 'wprequal' ); ?></label>
				</th>

				<td>
					<?php $value = empty( $fname ) ? '' : $fname; ?>
					<input type="text" name="contact[fname]" value="<?php esc_attr_e( $value ); ?>" id="fname" class="regular-text">
				</td>
			</tr>

			<tr>
				<th>
					<label for="lname"><?php _e( 'Last Name', 'wprequal' ); ?></label>
				</th>

				<td>
					<?php $value = empty( $lname ) ? '' : $lname; ?>
					<input type="text" name="contact[lname]" value="<?php esc_attr_e( $value ); ?>" id="lname" class="regular-text">
				</td>
			</tr>

			<tr>
				<th>
					<label for="email"><?php _e( 'Email Address', 'wprequal' ); ?></label>
				</th>

				<td>
					<?php $value = empty( $email ) ? '' : $email; ?>
					<input type="text" name="contact[email]" value="<?php esc_attr_e( $value ); ?>" id="email" class="regular-text">
				</td>
			</tr>

			<tr>
				<th>
					<label for="phone"><?php _e( 'Phone', 'wprequal' ); ?></label>
				</th>

				<td>
					<?php $value = empty( $phone ) ? '' : $phone; ?>
					<input type="text" name="contact[phone]" value="<?php esc_attr_e( $value ); ?>" id="phone" class="regular-text">
				</td>
			</tr>

			<tr>
				<th>
					<label for="comments"><?php _e( 'Comments', 'wprequal' ); ?></label>
				</th>

				<td>
					<?php $value = empty( $comments ) ? '' : $fname; ?>
					<textarea name="contact[comments]" id="comments" class="large-text" rows="8"><?php esc_textarea( $value ); ?></textarea>
				</td>
			</tr>

			<?php if ( $source_url = get_post_meta( get_the_ID(), 'source_url', TRUE ) ) { ?>

				<tr>

					<th>
						<label for="source-url"><?php _e( 'Source URL', 'wprequal' ); ?></label>
					</th>

					<td>
						<a href="<?php echo esc_url( $source_url ); ?>" id="source-url"><?php esc_html_e( $source_url ); ?></a>
					</td>

				</tr>

			<?php } ?>

		</tbody>

	</table>

</div>