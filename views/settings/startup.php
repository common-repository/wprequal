<?php
/**
 * Startup Form
 *
 * Admin startup form.
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="wprequal-wrap">

	<div class="wprequal-startup-wrapper">

		<div class="wprequal-wrap">

			<?php printf( '<img src="%s" alt="wprequal-logo"/>', esc_url( $logo ) ); ?>

		</div>

		<style>
			.hide { display: none; }
		</style>
		<script>
			jQuery(document).ready(function($){
				$('.opt-in').on('change', function(){
					$('.hide').slideToggle();
				});
			});
		</script>

		<h2><?php _e( 'API Access Token Required', 'wprequal' ); ?></h2>

		<form method="post">

			<table class="form-table">

				<tbody>

					<tr class="">
						<td colspan="2">
							<input type="checkbox" id="opt-in" class="opt-in"/>
							<?php _e( 'Please keep me up to date with all the latest security and feature updates.', 'wprequal' ); ?>
						</td>
					</tr>

					<tr class="hide">
						<th>
							<label for="name"><?php _e( 'Full Name', 'wprequal' ); ?></label>
						</th>
						<td>
							<input type="text" id="name" name="name" class="regular-text"/>
						</td>
					</tr>

					<tr class="hide">
						<th>
							<label for="email"><?php _e( 'Email', 'wprequal' ); ?></label>
						</th>
						<td>
							<input type="text" id="email" name="email" class="regular-text"/>
						</td>
					</tr>

					<tr>
						<td>
							<input type="hidden" name="wprequal_start_up" value="Y" />
							<?php wp_nonce_field( 'wprequal_start' ); ?>
							<input
								type="submit"
								name="Submit"
								class="button-primary"
								value="<?php esc_attr_e( 'Get Free API Access Token' ) ?>"
							/>
						</td>
					</tr>

				</tbody>

			</table>

		</form>

	</div>

</div>