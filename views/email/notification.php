<?php
/**
 * Notification
 *
 * Email sent for form notification.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

extract( $contact ); ?>

<html lang="en-US">

	<head>

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?php _e( 'WPrequal Lead', 'wprequal' ); ?></title>

		<style type="text/css">
			body{
				font-family: "Ek Mukta", sans-serif;
				font-size: 20px;
				font-weight: 200;
				line-height: 1.5;
				max-width:600px;
				text-align: left;
			}
			tr{
				clear: both;
				text-align: left;
			}
			td{
				padding: 9px 5px;
				text-align: left;
			}
			label {
				text-align: left;
			}
			.wprequal-logo{
				max-width:200px;
			}
			.button{
				padding:7px 9px;
				background:#15B658;
				color:#fff;
				text-decoration:none;
			}
			.button:hover{
				background:#ccc;
				color:#000;
			}
		</style>

	</head>

	<body>

		<div itemscope itemtype="https://schema.org/LocalBusiness">

			<h3><?php _e( 'Please contact your new WPrequal Lead ASAP.', 'wprequal' ); ?></h3>

			<table>

				<tbody>

					<?php if ( ! empty( $fname ) ) { ?>

						<tr>
							<th>
								<label for="fname"><?php _e( 'First Name', 'wprequal' ); ?></label>
							</th>

							<td id="fname"><?php esc_html_e( $fname ); ?></td>
						</tr>

					<?php } ?>

					<?php if ( ! empty( $lname ) ) { ?>

						<tr>
							<th>
								<label for="lname"><?php _e( 'Last Name', 'wprequal' ); ?></label>
							</th>

							<td id="lname"><?php esc_html_e( $lname ); ?></td>
						</tr>

					<?php } ?>

					<?php if ( ! empty( $email ) ) { ?>

						<tr>
							<th>
								<label for="email"><?php _e( 'Email Address', 'wprequal' ); ?></label>
							</th>

							<td id="email"><?php esc_html_e( $email ); ?></td>
						</tr>

					<?php } ?>

					<?php if ( ! empty( $phone ) ) { ?>

						<tr>
							<th>
								<label for="phone"><?php _e( 'Phone', 'wprequal' ); ?></label>
							</th>

							<td id="phone"><?php esc_html_e( $phone ); ?></td>
						</tr>

					<?php } ?>

					<?php if ( ! empty( $comments ) ) { ?>

						<tr>
							<th>
								<label for="comments"><?php _e( 'Comments', 'wprequal' ); ?></label>
							</th>

							<td id="comments"><?php esc_html_e( $comments ); ?></td>
						</tr>

					<?php } ?>

				</tbody>

			</table>

			<table>

				<tbody>

					<?php $fields = is_array( $fields ) ? $fields : array(); ?>

					<?php foreach ( $fields as $key => $value ) { ?>

						<tr>

							<th>
								<label for="<?php esc_attr_e( $key ); ?>"><?php esc_html_e( $labels[$key] ); ?></label>
							</th>

							<td id="<?php esc_attr_e( $key ); ?>">

								<?php Core::status( 1 ) ? esc_html_e( $value ) : view( 'buttons', 'go-premium' ); ?>

							</td>

						</tr>

					<?php } ?>

					<tr>

						<th>
							<label for="source-url"><?php esc_html_e( 'Source URL', 'wprequal' ); ?></label>
						</th>

						<td>
							<a href="<?php echo esc_url( $source_url ); ?>" id="source-url"><?php esc_html_e( $source_url ); ?></a>
						</td>

					</tr>

				</tbody>

			</table>

			<table>

				<tbody>

					<tr>
						<td><?php esc_html_e( 'WPrequal Leads' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'support@wprequal.com' ); ?></td>
					</tr>

					<tr>
						<td>
							<img src="<?php printf( '%s', esc_url( $logo ) ); ?>" alt="WPrequal logo" class="wprequal-logo">
						</td>
					</tr>

				</tbody>

			</table>

		</div>

	</body>

</html>