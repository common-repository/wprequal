<?php
/**
 * Help Buttons
 *
 * Admin help button row.
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<style>
	.form-table {
		width: auto;
	}
	.form-table td {
		padding: 4px 2px;
	}
	.clear {
		clear:both;
	}
</style>

<table class="clear form-table">

	<tbody>

		<tr><?php

			if ( ! Core::status( 1 ) ) { ?>

				<td>
					<a href="https://wprequal.com/product/premium-license/" target="_blank" class="button button-primary">
						<i class="far fa-thumbs-up"></i> <?php _e( 'Go Premium', 'wprequal' ); ?>
					</a>
				</td><?php


			}

			if ( ! Core::status( 2 ) ) { ?>

				<td>
					<a href="https://wprequal.com/product/pro-license/" target="_blank" class="button button-primary">
						<i class="far fa-hand-point-up"></i> <?php _e( 'Go Pro', 'wprequal' ); ?>
					</a>
				</td><?php

			}

			if ( Core::status( 1 ) ) { ?>

				<td>
					<a href="https://wprequal.com/my-account/" target="_blank" class="button button-primary">
						<i class="far fa-user"></i> <?php _e( 'My Account', 'wprequal' ); ?>
					</a>
				</td><?php
			}

			if ( Core::status( 2 ) ) { ?>

				<td>
					<a href="https://wprequal.com/my-account/connect_api/" target="_blank" class="button button-primary">
						<i class="far fa-plug"></i> <?php _e( 'Connect API', 'wprequal' ); ?>
					</a>
				</td><?php

			} ?>

			<td>
				<a href="https://wprequal.com/category/how-to/" target="_blank" class="button button-primary how-to">
					<i class="fas fa-chalkboard-teacher"></i> <?php _e( 'How-To', 'wprequal' ); ?>
				</a>
			</td>

			<td>
				<a href="https://wprequal.com/documentation-type/developers/" target="_blank" class="button button-primary developer">
					<i class="fas fa-code"></i> <?php _e( 'Developer', 'wprequal' ); ?>
				</a>
			</td>

			<td>
				<a href="https://wprequal.com/support/" target="_blank" class="button button-primary support">
					<i class="far fa-question-circle"></i> <?php _e( 'Support', 'wprequal' ); ?>
				</a>
			</td>

		</tr>

	</tbody>

</table>
