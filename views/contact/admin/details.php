<?php
/**
 * Details
 *
 * Contact form details
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<table class="form-table">

	<tbody>

		<tr>
			<th>
				<label for="button_text"><?php _e( 'Button Text', 'wprequal' ); ?></label>
			</th>

			<td><input type="text" id="button_text" name="details[button_text]" value="<?php esc_attr_e( $button_text ); ?>" class="regular-text"></td>
		</tr>

		<tr>
			<th>
				<label for="from_name"><?php _e( 'From Name', 'wprequal' ); ?></label>
			</th>

			<td><input type="text" id="from_name" name="details[from_name]" value="<?php esc_attr_e( $from_name ); ?>" class="regular-text"></td>

			<td><?php _e( 'What name should your leads be sent from?', 'wprequal' ); ?></td>
		</tr>

		<tr>
			<th>
				<label for="from_email"><?php _e( 'From Email Address', 'wprequal' ); ?></label>
			</th>

			<td><input type="text" id="from_email" name="details[from_email]" value="<?php esc_attr_e( $from_email ); ?>" class="regular-text"></td>

			<td><?php _e( 'What email should your leads be sent from?', 'wprequal' ); ?></td>
		</tr>

		<tr>
			<th>
				<label for="to_email"><?php _e( 'To Email Address', 'wprequal' ); ?></label>
			</th>

			<td><input type="text" id="to_email" name="details[to_email]" value="<?php esc_attr_e( $to_email ); ?>" class="regular-text"></td>

			<td><?php _e( 'Where you would like your WPrequal lead emails sent?', 'wprequal' ); ?></td>
		</tr>

		<tr>
			<th>
				<label for="bcc_email"><?php _e( 'Bcc Email Address', 'wprequal' ); ?></label>
			</th>

			<?php if ( Core::status( 1 ) ) { ?>

				<td><input type="text" id="bcc_email" name="details[bcc_email]" value="<?php esc_attr_e( $bcc_email ); ?>" class="regular-text"></td>

			<?php } else { ?>

				<?php view( 'buttons', 'go-premium' ); ?>
				<input type="hidden" name="details[bcc_email]" value=""/>

			<?php } ?>
		</tr>

		<tr>
			<th>
				<label for="sms_text"><?php _e( 'SMS Text Carrier Gateway', 'wprequal' ); ?></label>
			</th>

			<?php if ( Core::status( 1 ) ) { ?>

				<td><input type="text" id="sms_text" name="details[sms_text]" value="<?php esc_attr_e( $sms_text ); ?>" class="regular-text"></td>

				<td><?php printf( '%s <a href="%s" target="_blank">%s</a>', esc_html__( 'Where you would like your WPrequal notification text sent?', 'wprequal' ), esc_url( 'https://wprequal.com/mobile-carrier-gateway-addresses/' ), esc_html__( 'Find Your Carrier Gateway', 'wprequal' ) ); ?></td>

			<?php } else { ?>

				<?php view( 'buttons', 'go-premium' ); ?>
				<input type="hidden" name="details[sms_text]" value=""/>

			<?php } ?>
		</tr>

	</tbody>

</table>