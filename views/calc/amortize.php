<?php
/**
 * Months
 *
 * Calc months.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$principal_label  = get_option( 'wprequal_amortize_principal_label', 'Principal' );
$interest_label  = get_option( 'wprequal_amortize_interest_label', 'Interest' ); ?>

<!-- Start amortization Section -->
<form class="wprequal-calc wprequal-amortize">

	<div class="wpq-container">

		<div class="wpq-row"><?php

			Calc::view( 'title', 'wprequal_amortize_title' );
			Calc::view( 'purchase-price' );
			Calc::view( 'down-payment' );
			Calc::view( 'loan-term' );
			Calc::view( 'interest-rate' ); ?>

			<div class="amortization">

				<div class="results">
					<?php Calc::view( 'payment' ); ?>

					<div class="principal wpq-col-12">
						<?php esc_html_e( $principal_label ); ?> <?php Calc::currency( '<span></span>' ); ?>
					</div>
					<div class="interest wpq-col-12">
						<?php esc_html_e( $interest_label ); ?> <?php Calc::currency( '<span></span>' ); ?>
					</div>
					<div class="balance wpq-col-12">
						<?php _e( 'Balance:', 'wprequal' ); ?> <?php Calc::currency( '<span></span>' ); ?>
					</div>

					<div class="month wpq-col-12">
						<?php _e( 'Month:', 'wprequal' ); ?> <span></span>
					</div>
					<?php Calc::view( 'months' ); ?>

				</div>

				<div class="graph">
					<canvas id="amortize-graph" class="amortize-graph"></canvas>
				</div>

			</div>

			<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

		</div>

	</div>

</form>
<!-- End amortization Section -->
