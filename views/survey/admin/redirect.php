<?php
/**
 * Redirect
 *
 * Inputs for redirect slide.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<?php view( 'survey/admin', 'slide-start' ); ?>

<tbody>

	<tr>

		<th>
			<a href="https://wprequal.com/how-to-use-custom-query-args-with-redirect-url/" target="_blank">
				<?php _e( 'How To Guide', 'wprequal' ); ?>
			</a>
		</th>

	</tr>

	<tr class="large-text">

		<th scope="row">
			<label for="{key}_redirect_endpoint"><?php _e( 'Redirect Endpoint', 'wprequal' ); ?></label>
		</th>

		<td>

			<input type="text" name="slide[{key}][endpoint]" id="{key}_redirect_endpoint" class="large-text wpq-required" placeholder="https://yourpage.com/something/">

		</td>

	</tr>

	<tr class="large-text">

		<th scope="row">
			<label for="{key}_redirect_fname"><?php _e( 'First Name Key', 'wprequal' ); ?></label>
		</th>

		<td>

			<input type="text" name="slide[{key}][fname]" id="{key}_redirect_fname" class="large-text">

		</td>

	</tr>

	<tr class="large-text">

		<th scope="row">
			<label for="{key}_redirect_lname"><?php _e( 'Last Name Key', 'wprequal' ); ?></label>
		</th>

		<td>

			<input type="text" name="slide[{key}][lname]" id="{key}_redirect_lname" class="large-text">

		</td>

	</tr>

	<tr class="large-text">

		<th scope="row">
			<label for="{key}_redirect_email"><?php _e( 'Email Key', 'wprequal' ); ?></label>
		</th>

		<td>

			<input type="text" name="slide[{key}][email]" id="{key}_redirect_email" class="large-text">

		</td>

	</tr>

	<tr class="large-text">

		<th scope="row">
			<label for="{key}_redirect_phone"><?php _e( 'Phone Key', 'wprequal' ); ?></label>
		</th>

		<td>

			<input type="text" name="slide[{key}][phone]" id="{key}_redirect_phone" class="large-text">

		</td>

	</tr>

	<tr class="large-text">

		<th scope="row">
			<label for="{key}_redirect_additional"><?php _e( 'Additional Args', 'wprequal' ); ?></label>
		</th>

		<td>

			<input type="text" name="slide[{key}][additional]" id="{key}_redirect_additional" class="large-text" placeholder="acount_id=1234&amp;company=mycompanyname&amp;agent=myname">

		</td>

		<td>(optional) Args will be appended at the end of URL.</td>

	</tr>

</tbody>

<?php view( 'survey/admin', 'slide-end' ); ?>
